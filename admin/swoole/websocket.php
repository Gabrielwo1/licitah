<?php
/**
 * Servidor WebSocket Modular
 * 
 * Escaneia e carrega dinamicamente módulos WebSocket
 * 
 * Para executar:
 * php websocket_server.php
 */

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

class WebSocketModular
{
    private $server;
    private $config;
    private $modules = [];
    private $moduleHandlers = [];
    private $connections = [];
    private $rooms = [];
    
    public function __construct()
    {
        $this->config = [
            'host' => '0.0.0.0',
            'port' => 9501,
            'worker_num' => 4,
            'max_request' => 10000,
            'modules_path' => __DIR__ . '/conteudo/modulos'
        ];
        
        $this->server = new Server($this->config['host'], $this->config['port']);
        $this->setupServer();
        $this->setupEvents();
    }
    
    private function setupServer()
    {
        $this->server->set([
            'worker_num' => $this->config['worker_num'],
            'max_request' => $this->config['max_request'],
            'daemonize' => false,
            'log_file' => __DIR__ . '/logs/websocket.log',
            'pid_file' => __DIR__ . '/logs/websocket.pid',
            'heartbeat_check_interval' => 60,
            'heartbeat_idle_time' => 600,
        ]);
    }
    
    private function setupEvents()
    {
        $this->server->on('start', [$this, 'onStart']);
        $this->server->on('workerStart', [$this, 'onWorkerStart']);
        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('close', [$this, 'onClose']);
        $this->server->on('request', [$this, 'onRequest']);
    }
    
    public function onStart($server)
    {
        echo "Servidor WebSocket iniciado em ws://{$this->config['host']}:{$this->config['port']}\n";
        echo "HTTP endpoint em http://{$this->config['host']}:{$this->config['port']}\n";
        echo "PID: {$server->master_pid}\n";
        
        if (!is_dir(__DIR__ . '/logs')) {
            mkdir(__DIR__ . '/logs', 0755, true);
        }
    }
    
    public function onWorkerStart($server, $workerId)
    {
        echo "Worker #{$workerId} iniciado\n";
        
        if ($workerId === 0) {
            $this->loadModules();
            echo "Módulos WebSocket carregados: " . count($this->modules) . "\n";
        }
    }
    
    /**
     * Escaneia e carrega módulos WebSocket
     */
    private function loadModules()
    {
        $modulesPath = $this->config['modules_path'];
        
        if (!is_dir($modulesPath)) {
            echo "Diretório de módulos não encontrado: {$modulesPath}\n";
            return;
        }
        
        $directories = scandir($modulesPath);
        
        foreach ($directories as $dir) {
            if ($dir === '.' || $dir === '..') continue;
            
            $modulePath = $modulesPath . '/' . $dir;
            $websocketFile = $modulePath . '/admins/websocket.php';
            
            if (is_dir($modulePath) && file_exists($websocketFile)) {
                $this->loadModule($dir, $websocketFile);
            }
        }
    }
    
    /**
     * Carrega um módulo específico
     */
    private function loadModule($moduleName, $websocketFile)
    {
        try {
            // Contexto para o módulo
            $moduleContext = new WebSocketModuleContext($this, $moduleName);
            
            // Inclui o arquivo do módulo
            $moduleHandlers = include $websocketFile;
            
            if (is_array($moduleHandlers)) {
                $this->modules[$moduleName] = [
                    'file' => $websocketFile,
                    'handlers' => $moduleHandlers,
                    'context' => $moduleContext,
                    'loaded_at' => time()
                ];
                
                // Registra handlers
                foreach ($moduleHandlers as $event => $handler) {
                    if (!isset($this->moduleHandlers[$event])) {
                        $this->moduleHandlers[$event] = [];
                    }
                    $this->moduleHandlers[$event][$moduleName] = $handler;
                }
                
                echo "Módulo carregado: {$moduleName}\n";
                
                // Chama callback de inicialização se existir
                if (isset($moduleHandlers['onModuleInit'])) {
                    call_user_func($moduleHandlers['onModuleInit'], $moduleContext);
                }
            }
            
        } catch (Exception $e) {
            echo "Erro ao carregar módulo {$moduleName}: " . $e->getMessage() . "\n";
        }
    }
    
    public function onOpen($server, $request)
    {
        $fd = $request->fd;
        
        // Informações da conexão
        $this->connections[$fd] = [
            'ip' => $request->server['remote_addr'] ?? 'unknown',
            'user_agent' => $request->header['user-agent'] ?? 'unknown',
            'connected_at' => time(),
            'user_id' => null,
            'modules' => [],
            'rooms' => []
        ];
        
        echo "Cliente conectado: {$fd}\n";
        
        // Chama handlers dos módulos
        $this->callModuleHandlers('onConnect', $fd, $request);
        
        // Mensagem de boas-vindas
        $welcome = [
            'type' => 'system',
            'event' => 'connected',
            'data' => [
                'fd' => $fd,
                'modules' => array_keys($this->modules),
                'timestamp' => time()
            ]
        ];
        
        $server->push($fd, json_encode($welcome));
    }
    
    public function onMessage($server, $frame)
    {
        $fd = $frame->fd;
        $data = json_decode($frame->data, true);
        
        if (!$data) {
            $this->sendError($fd, 'JSON inválido');
            return;
        }
        
        $module = $data['module'] ?? null;
        $event = $data['event'] ?? null;
        $payload = $data['data'] ?? [];
        
        echo "Mensagem recebida - FD: {$fd}, Módulo: {$module}, Evento: {$event}\n";
        
        // Eventos do sistema
        if ($module === 'system') {
            $this->handleSystemEvent($fd, $event, $payload);
            return;
        }
        
        // Valida módulo
        if (!$module || !isset($this->modules[$module])) {
            $this->sendError($fd, "Módulo '{$module}' não encontrado");
            return;
        }
        
        // Chama handler específico do módulo
        if (isset($this->moduleHandlers[$event][$module])) {
            try {
                $context = $this->modules[$module]['context'];
                $context->setCurrentConnection($fd);
                
                call_user_func(
                    $this->moduleHandlers[$event][$module], 
                    $context, 
                    $payload, 
                    $fd
                );
                
            } catch (Exception $e) {
                $this->sendError($fd, "Erro no módulo: " . $e->getMessage());
                echo "Erro no módulo {$module}: " . $e->getMessage() . "\n";
            }
        } else {
            $this->sendError($fd, "Evento '{$event}' não encontrado no módulo '{$module}'");
        }
    }
    
    public function onClose($server, $fd)
    {
        echo "Cliente desconectado: {$fd}\n";
        
        // Remove das salas
        if (isset($this->connections[$fd]['rooms'])) {
            foreach ($this->connections[$fd]['rooms'] as $room) {
                $this->leaveRoom($fd, $room);
            }
        }
        
        // Chama handlers dos módulos
        $this->callModuleHandlers('onDisconnect', $fd);
        
        // Remove conexão
        unset($this->connections[$fd]);
    }
    
    public function onRequest($request, $response)
    {
        $uri = $request->server['request_uri'];
        
        // API para informações do servidor
        if ($uri === '/websocket/info') {
            $info = [
                'modules' => array_keys($this->modules),
                'connections' => count($this->connections),
                'rooms' => array_keys($this->rooms),
                'uptime' => time() - $this->server->stats()['start_time']
            ];
            
            $response->header('Content-Type', 'application/json');
            $response->end(json_encode($info, JSON_UNESCAPED_UNICODE));
            return;
        }
        
        // API para recarregar módulos
        if ($uri === '/websocket/reload') {
            $this->reloadModules();
            $response->header('Content-Type', 'application/json');
            $response->end(json_encode(['success' => true, 'message' => 'Módulos recarregados']));
            return;
        }
        
        $response->status(404);
        $response->end('Not Found');
    }
    
    /**
     * Manipula eventos do sistema
     */
    private function handleSystemEvent($fd, $event, $payload)
    {
        switch ($event) {
            case 'auth':
                $this->handleAuth($fd, $payload);
                break;
                
            case 'join_room':
                $room = $payload['room'] ?? null;
                if ($room) {
                    $this->joinRoom($fd, $room);
                }
                break;
                
            case 'leave_room':
                $room = $payload['room'] ?? null;
                if ($room) {
                    $this->leaveRoom($fd, $room);
                }
                break;
                
            case 'ping':
                $this->server->push($fd, json_encode([
                    'type' => 'system',
                    'event' => 'pong',
                    'data' => ['timestamp' => time()]
                ]));
                break;
        }
    }
    
    /**
     * Autenticação de usuário
     */
    private function handleAuth($fd, $payload)
    {
        $token = $payload['token'] ?? null;
        
        if (!$token) {
            $this->sendError($fd, 'Token requerido');
            return;
        }
        
        // Aqui você implementaria sua lógica de autenticação
        // Por exemplo, verificar JWT ou sessão
        $userId = $this->validateToken($token);
        
        if ($userId) {
            $this->connections[$fd]['user_id'] = $userId;
            
            $response = [
                'type' => 'system',
                'event' => 'auth_success',
                'data' => ['user_id' => $userId]
            ];
            
            $this->server->push($fd, json_encode($response));
            
            // Chama handlers de autenticação dos módulos
            $this->callModuleHandlers('onAuth', $fd, $userId);
        } else {
            $this->sendError($fd, 'Token inválido');
        }
    }
    
    /**
     * Gerenciamento de salas
     */
    public function joinRoom($fd, $room)
    {
        if (!isset($this->rooms[$room])) {
            $this->rooms[$room] = [];
        }
        
        $this->rooms[$room][$fd] = true;
        $this->connections[$fd]['rooms'][] = $room;
        
        echo "Cliente {$fd} entrou na sala: {$room}\n";
        
        // Notifica outros na sala
        $this->broadcastToRoom($room, [
            'type' => 'system',
            'event' => 'user_joined',
            'data' => ['fd' => $fd, 'room' => $room]
        ], $fd);
    }
    
    public function leaveRoom($fd, $room)
    {
        if (isset($this->rooms[$room][$fd])) {
            unset($this->rooms[$room][$fd]);
            
            if (empty($this->rooms[$room])) {
                unset($this->rooms[$room]);
            }
        }
        
        if (isset($this->connections[$fd]['rooms'])) {
            $this->connections[$fd]['rooms'] = array_filter(
                $this->connections[$fd]['rooms'], 
                function($r) use ($room) { return $r !== $room; }
            );
        }
        
        echo "Cliente {$fd} saiu da sala: {$room}\n";
    }
    
    /**
     * Broadcast para sala
     */
    public function broadcastToRoom($room, $message, $excludeFd = null)
    {
        if (!isset($this->rooms[$room])) return;
        
        $json = is_string($message) ? $message : json_encode($message);
        
        foreach ($this->rooms[$room] as $fd => $active) {
            if ($fd !== $excludeFd) {
                $this->server->push($fd, $json);
            }
        }
    }
    
    /**
     * Chama handlers de todos os módulos
     */
    private function callModuleHandlers($event, ...$args)
    {
        if (!isset($this->moduleHandlers[$event])) return;
        
        foreach ($this->moduleHandlers[$event] as $module => $handler) {
            try {
                $context = $this->modules[$module]['context'];
                call_user_func($handler, $context, ...$args);
            } catch (Exception $e) {
                echo "Erro no handler {$event} do módulo {$module}: " . $e->getMessage() . "\n";
            }
        }
    }
    
    /**
     * Recarrega módulos
     */
    public function reloadModules()
    {
        echo "Recarregando módulos...\n";
        $this->modules = [];
        $this->moduleHandlers = [];
        $this->loadModules();
        echo "Módulos recarregados: " . count($this->modules) . "\n";
    }
    
    /**
     * Envia erro para cliente
     */
    private function sendError($fd, $message)
    {
        $error = [
            'type' => 'error',
            'message' => $message,
            'timestamp' => time()
        ];
        
        $this->server->push($fd, json_encode($error));
    }
    
    /**
     * Valida token (implementar sua lógica)
     */
    private function validateToken($token)
    {
        // Implementar validação de token
        // Pode usar JWT, verificar no banco, etc.
        return $token === 'valid_token' ? 123 : false;
    }
    
    public function getServer()
    {
        return $this->server;
    }
    
    public function getConnections()
    {
        return $this->connections;
    }
    
    public function start()
    {
        $this->server->start();
    }
}

/**
 * Contexto para módulos WebSocket
 */
class WebSocketModuleContext
{
    private $server;
    private $moduleName;
    private $currentFd;
    
    public function __construct($server, $moduleName)
    {
        $this->server = $server;
        $this->moduleName = $moduleName;
    }
    
    public function setCurrentConnection($fd)
    {
        $this->currentFd = $fd;
    }
    
    /**
     * Envia mensagem para cliente atual
     */
    public function send($data)
    {
        if (!$this->currentFd) return false;
        
        $message = [
            'type' => 'module',
            'module' => $this->moduleName,
            'data' => $data,
            'timestamp' => time()
        ];
        
        return $this->server->getServer()->push($this->currentFd, json_encode($message));
    }
    
    /**
     * Envia mensagem para cliente específico
     */
    public function sendTo($fd, $data)
    {
        $message = [
            'type' => 'module',
            'module' => $this->moduleName,
            'data' => $data,
            'timestamp' => time()
        ];
        
        return $this->server->getServer()->push($fd, json_encode($message));
    }
    
    /**
     * Broadcast para todos os clientes
     */
    public function broadcast($data, $excludeFd = null)
    {
        $message = [
            'type' => 'module',
            'module' => $this->moduleName,
            'data' => $data,
            'timestamp' => time()
        ];
        
        $json = json_encode($message);
        $connections = $this->server->getConnections();
        
        foreach ($connections as $fd => $info) {
            if ($fd !== $excludeFd) {
                $this->server->getServer()->push($fd, $json);
            }
        }
    }
    
    /**
     * Broadcast para sala
     */
    public function broadcastToRoom($room, $data, $excludeFd = null)
    {
        $message = [
            'type' => 'module',
            'module' => $this->moduleName,
            'data' => $data,
            'timestamp' => time()
        ];
        
        $this->server->broadcastToRoom($room, $message, $excludeFd);
    }
    
    /**
     * Adiciona cliente à sala
     */
    public function joinRoom($room, $fd = null)
    {
        $fd = $fd ?? $this->currentFd;
        $this->server->joinRoom($fd, $room);
    }
    
    /**
     * Remove cliente da sala
     */
    public function leaveRoom($room, $fd = null)
    {
        $fd = $fd ?? $this->currentFd;
        $this->server->leaveRoom($fd, $room);
    }
    
    /**
     * Obtém informações da conexão
     */
    public function getConnectionInfo($fd = null)
    {
        $fd = $fd ?? $this->currentFd;
        $connections = $this->server->getConnections();
        return $connections[$fd] ?? null;
    }
    
    /**
     * Obtém ID do usuário
     */
    public function getUserId($fd = null)
    {
        $info = $this->getConnectionInfo($fd);
        return $info['user_id'] ?? null;
    }
}

// Inicialização
if (php_sapi_name() === 'cli') {
    $websocket = new WebSocketModular();
    
    pcntl_signal(SIGTERM, function() use ($websocket) {
        echo "Parando servidor WebSocket...\n";
        exit(0);
    });
    
    pcntl_signal(SIGINT, function() use ($websocket) {
        echo "Parando servidor WebSocket...\n";
        exit(0);
    });
    
    $websocket->start();
} else {
    echo "Execute via CLI\n";
    exit(1);
}
?>