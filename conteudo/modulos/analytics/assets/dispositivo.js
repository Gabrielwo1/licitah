class Dispositivo {
  constructor() {
    this.fingerprint = null;
    this.dadosFixos = null;
    this.dadosLocalizacao = null;
    this.dadosMomento = null;
    this.dadosExternos = null;
    this.ultimaAtividade = Date.now();
    
    // URLs das APIs gratuitas
    this.apis = {
      ipGeolocation: [
        'https://ipapi.co/json/',
        'https://ip-api.com/json/',
        'https://freeipapi.com/api/json/',
        'https://ipinfo.io/json'
      ],
      speedTest: 'https://speed.cloudflare.com/meta'
    };
  }

  /**
   * Obtém dados FIXOS do dispositivo (coletados uma única vez)
   * @returns {Promise<Object>} Dados que não mudam durante a sessão
   */
  async obterDadosFixos() {
    if (this.dadosFixos) {
      return this.dadosFixos;
    }

    this.dadosFixos = {
      // Identificação do dispositivo
      fingerprint: this.obterFingerprint(),
      canvasFingerprint: this.obterCanvasFingerprint(),
      
      // Informações do navegador
      userAgent: navigator.userAgent,
      platform: navigator.platform,
      language: navigator.language,
      languages: navigator.languages || [navigator.language],
      vendor: navigator.vendor || 'unknown',
      appName: navigator.appName || 'unknown',
      appVersion: navigator.appVersion || 'unknown',
      product: navigator.product || 'unknown',
      productSub: navigator.productSub || 'unknown',
      buildID: navigator.buildID || 'unknown',
      oscpu: navigator.oscpu || 'unknown',
      
      // Especificações de hardware
      screenWidth: window.screen.width,
      screenHeight: window.screen.height,
      availWidth: window.screen.availWidth,
      availHeight: window.screen.availHeight,
      colorDepth: screen.colorDepth,
      pixelDepth: screen.pixelDepth || screen.colorDepth,
      pixelRatio: window.devicePixelRatio || 1,
      cores: navigator.hardwareConcurrency || null,
      memory: navigator.deviceMemory || null,
      
      // Capacidades do dispositivo
      cookiesEnabled: navigator.cookieEnabled,
      isTouch: this.isTouchDevice(),
      maxTouchPoints: navigator.maxTouchPoints || 0,
      javaEnabled: this.verificarJavaEnabled(),
      webdriver: navigator.webdriver || false,
      pdfViewerEnabled: navigator.pdfViewerEnabled || false,
      
      // Capacidades de mídia
      mediaCapabilities: this.obterCapacidadesMedia(),
      audioFormats: this.obterFormatosAudio(),
      videoFormats: this.obterFormatosVideo(),
      
      // APIs disponíveis
      apisDisponiveis: this.verificarAPIsDisponiveis(),
      
      // Informações de sistema
      timezone: this.obterTimezone(),
      gpu: this.obterInformacoesGPU(),
      webglFingerprint: this.obterWebGLFingerprint(),
      tipoDispositivo: this.detectarTipoDispositivo(),
      
      // Configurações do sistema
      darkMode: this.verificarModoEscuro(),
      reducedMotion: this.verificarMovimentoReduzido(),
      highContrast: this.verificarAltoContraste(),
      
      // Fontes disponíveis (amostra)
      fontesDisponiveis: this.obterFontesDisponiveis(),
      
      // Plugins (limitado)
      plugins: this.obterPlugins(),
      
      // Dados coletados apenas uma vez
      timestampPrimeiraColeta: Date.now()
    };

    return this.dadosFixos;
  }

  /**
   * Obtém dados de LOCALIZAÇÃO (coletados quando necessário)
   * @returns {Promise<Object|null>} Dados de localização ou null se não disponível
   */
  async obterDadosLocalizacao() {
    try {
      // Verifica se geolocalização está habilitada no sistema
      const geoEnabled = await this.verificarGeolocalizacaoHabilitada();
      if (!geoEnabled) {
        return null;
      }

      // Verifica se já temos dados de localização recentes (últimos 5 minutos)
      if (this.dadosLocalizacao && 
          (Date.now() - this.dadosLocalizacao.timestamp) < 300000) {
        return this.dadosLocalizacao;
      }

      // Coleta novos dados de localização
      const locationData = await this.coletarDadosLocalizacao();
      
      this.dadosLocalizacao = {
        latitude: locationData.latitude,
        longitude: locationData.longitude,
        accuracy: locationData.accuracy,
        altitude: locationData.altitude,
        altitudeAccuracy: locationData.altitudeAccuracy,
        heading: locationData.heading,
        speed: locationData.speed,
        timestamp: locationData.timestamp,
        coletadoEm: Date.now()
      };

      // Salva no localStorage
      defineLocal("latitude", locationData.latitude);
      defineLocal("longitude", locationData.longitude);
      defineLocal("ultimaLocalizacao", JSON.stringify(this.dadosLocalizacao));

      return this.dadosLocalizacao;
    } catch (error) {
      console.warn("Erro ao obter dados de localização:", error);
      return null;
    }
  }

  /**
   * Obtém dados EXTERNOS via APIs gratuitas (IP público, localização por IP, etc.)
   * @returns {Promise<Object|null>} Dados externos ou null se não disponível
   */
  async obterDadosExternos() {
    try {
      // Verifica cache (válido por 1 hora)
      if (this.dadosExternos && 
          (Date.now() - this.dadosExternos.coletadoEm) < 3600000) {
        return this.dadosExternos;
      }

      const dadosIP = await this.obterDadosIPExterno();
      const dadosVelocidade = await this.testarVelocidadeInternet();

      this.dadosExternos = {
        // Dados de IP e localização externa
        ipPublico: dadosIP?.ip || null,
        pais: dadosIP?.country || null,
        paisCodigo: dadosIP?.country_code || null,
        estado: dadosIP?.region || null,
        cidade: dadosIP?.city || null,
        cep: dadosIP?.postal || null,
        fuso: dadosIP?.timezone || null,
        latitudeIP: dadosIP?.latitude || null,
        longitudeIP: dadosIP?.longitude || null,
        
        // Dados de ISP
        isp: dadosIP?.isp || null,
        org: dadosIP?.org || null,
        asn: dadosIP?.asn || null,
        
        // Dados de segurança
        proxy: dadosIP?.proxy || false,
        vpn: dadosIP?.vpn || false,
        tor: dadosIP?.tor || false,
        hosting: dadosIP?.hosting || false,
        mobile: dadosIP?.mobile || false,
        
        // Dados de velocidade da internet
        velocidadeDownload: dadosVelocidade?.download || null,
        velocidadeUpload: dadosVelocidade?.upload || null,
        latencia: dadosVelocidade?.ping || null,
        jitter: dadosVelocidade?.jitter || null,
        
        // Timestamp
        coletadoEm: Date.now()
      };

      return this.dadosExternos;
    } catch (error) {
      console.warn("Erro ao obter dados externos:", error);
      return null;
    }
  }

  /**
   * Obtém dados do MOMENTO atual (coletados frequentemente)
   * @returns {Promise<Object>} Dados que mudam constantemente
   */
  async obterDadosMomento() {
    this.dadosMomento = {
      // Dados de rede
      conexao: this.obterInformacoesConexao(),
      online: navigator.onLine,
      
      // Dados de bateria
      bateria: await this.obterInformacoesBateria(),
      
      // Dados de tela/janela
      orientacao: this.obterOrientacaoTela(),
      windowWidth: window.innerWidth,
      windowHeight: window.innerHeight,
      
      // Dados de performance
      memory: this.obterMemoriaAtual(),
      performanceTiming: this.obterPerformanceTiming(),
      navigationTiming: this.obterNavigationTiming(),
      
      // Dados de tempo
      timestamp: Date.now(),
      timezone: new Date().getTimezoneOffset(),
      
      // Dados de foco/visibilidade
      documentVisible: !document.hidden,
      hasFocus: document.hasFocus(),
      
      // Dados de scroll (se aplicável)
      scrollX: window.scrollX || window.pageXOffset || 0,
      scrollY: window.scrollY || window.pageYOffset || 0,
      
      // Dados de interação
      tempoInatividade: this.obterTempoInatividade(),
      
      // Sensores (se disponíveis)
      sensores: await this.obterDadosSensores(),
      
      // URL atual
      url: window.location.href,
      referrer: document.referrer || null
    };

    return this.dadosMomento;
  }

  /**
   * Obtém informações COMPLETAS (todos os dados juntos)
   * @returns {Promise<Object>} Objeto com todos os dados separados
   */
  async obterInformacoesCompletas() {
    const [dadosFixos, dadosLocalizacao, dadosExternos, dadosMomento] = await Promise.all([
      this.obterDadosFixos(),
      this.obterDadosLocalizacao(),
      this.obterDadosExternos(),
      this.obterDadosMomento()
    ]);

    return {
      fixos: dadosFixos,
      localizacao: dadosLocalizacao,
      externos: dadosExternos,
      momento: dadosMomento
    };
  }

  // ===== MÉTODOS PARA DADOS EXTERNOS (APIs GRATUITAS) =====

  /**
   * Obtém dados de IP público e geolocalização via APIs externas
   * @returns {Promise<Object|null>} Dados do IP ou null se falhou
   */
  async obterDadosIPExterno() {
    // Tenta múltiplas APIs em sequência para garantir disponibilidade
    for (const apiUrl of this.apis.ipGeolocation) {
      try {
        const response = await fetch(apiUrl, {
          method: 'GET',
          timeout: 5000,
          headers: {
            'Accept': 'application/json'
          }
        });

        if (response.ok) {
          const data = await response.json();
          
          // Normaliza os dados de diferentes APIs
          return this.normalizarDadosIP(data, apiUrl);
        }
      } catch (error) {
        console.warn(`Falha na API ${apiUrl}:`, error);
        continue;
      }
    }
    
    return null;
  }

  /**
   * Normaliza dados de IP de diferentes provedores
   * @param {Object} data Dados brutos da API
   * @param {string} apiUrl URL da API usada
   * @returns {Object} Dados normalizados
   */
  normalizarDadosIP(data, apiUrl) {
    const normalizado = {};

    // Mapeia campos comuns independente da API
    if (apiUrl.includes('ipapi.co')) {
      normalizado.ip = data.ip;
      normalizado.country = data.country_name;
      normalizado.country_code = data.country_code;
      normalizado.region = data.region;
      normalizado.city = data.city;
      normalizado.postal = data.postal;
      normalizado.latitude = data.latitude;
      normalizado.longitude = data.longitude;
      normalizado.timezone = data.timezone;
      normalizado.isp = data.org;
      normalizado.asn = data.asn;
    } else if (apiUrl.includes('ip-api.com')) {
      normalizado.ip = data.query;
      normalizado.country = data.country;
      normalizado.country_code = data.countryCode;
      normalizado.region = data.regionName;
      normalizado.city = data.city;
      normalizado.postal = data.zip;
      normalizado.latitude = data.lat;
      normalizado.longitude = data.lon;
      normalizado.timezone = data.timezone;
      normalizado.isp = data.isp;
      normalizado.org = data.org;
      normalizado.asn = data.as;
      normalizado.proxy = data.proxy;
      normalizado.mobile = data.mobile;
      normalizado.hosting = data.hosting;
    } else if (apiUrl.includes('freeipapi.com')) {
      normalizado.ip = data.ipAddress;
      normalizado.country = data.countryName;
      normalizado.country_code = data.countryCode;
      normalizado.region = data.regionName;
      normalizado.city = data.cityName;
      normalizado.postal = data.zipCode;
      normalizado.latitude = data.latitude;
      normalizado.longitude = data.longitude;
      normalizado.timezone = data.timeZone;
      normalizado.isp = data.isProxy ? 'proxy' : null;
    } else if (apiUrl.includes('ipinfo.io')) {
      normalizado.ip = data.ip;
      normalizado.country = data.country;
      normalizado.region = data.region;
      normalizado.city = data.city;
      normalizado.postal = data.postal;
      if (data.loc) {
        const [lat, lon] = data.loc.split(',');
        normalizado.latitude = parseFloat(lat);
        normalizado.longitude = parseFloat(lon);
      }
      normalizado.timezone = data.timezone;
      normalizado.isp = data.org;
    }

    return normalizado;
  }

  /**
   * Testa velocidade da internet usando técnicas cliente-side
   * @returns {Promise<Object|null>} Dados de velocidade ou null se falhou
   */
  async testarVelocidadeInternet() {
    try {
      // Teste de latência (ping)
      const latencia = await this.testarLatencia();
      
      // Teste de download (usando uma imagem pequena)
      const velocidadeDownload = await this.testarVelocidadeDownload();
      
      // Teste de upload não é possível de forma confiável via cliente
      
      return {
        ping: latencia,
        download: velocidadeDownload,
        upload: null, // Não disponível via cliente
        jitter: null, // Precisaria de múltiplos testes
        testadoEm: Date.now()
      };
    } catch (error) {
      console.warn("Erro no teste de velocidade:", error);
      return null;
    }
  }

  /**
   * Testa latência da conexão
   * @returns {Promise<number>} Latência em ms
   */
  async testarLatencia() {
    const urls = [
      'https://www.google.com/favicon.ico',
      'https://www.cloudflare.com/favicon.ico',
      'https://www.github.com/favicon.ico'
    ];
    
    const tempos = [];
    
    for (const url of urls) {
      try {
        const inicio = performance.now();
        await fetch(url, { method: 'HEAD', cache: 'no-cache' });
        const fim = performance.now();
        tempos.push(fim - inicio);
      } catch (error) {
        // Ignora erros e continua com próxima URL
      }
    }
    
    if (tempos.length === 0) return null;
    
    // Retorna a média dos tempos válidos
    return Math.round(tempos.reduce((a, b) => a + b) / tempos.length);
  }

  /**
   * Testa velocidade de download usando uma imagem de teste
   * @returns {Promise<number>} Velocidade em Mbps
   */
  async testarVelocidadeDownload() {
    try {
      // URL de uma imagem pequena para teste (1MB aproximadamente)
      const testUrl = 'https://via.placeholder.com/1000x1000.jpg';
      
      const inicio = performance.now();
      const response = await fetch(testUrl, { cache: 'no-cache' });
      const blob = await response.blob();
      const fim = performance.now();
      
      const tempoSegundos = (fim - inicio) / 1000;
      const tamanhoMB = blob.size / (1024 * 1024);
      const velocidadeMbps = (tamanhoMB * 8) / tempoSegundos; // Convert to Mbps
      
      return Math.round(velocidadeMbps * 100) / 100; // 2 decimais
    } catch (error) {
      return null;
    }
  }

  // ===== MÉTODOS AUXILIARES EXISTENTES =====

  /**
   * Detecta tipo de dispositivo
   * @returns {number} 0: Desconhecido, 1: Mobile, 2: Desktop, 3: Tablet
   */
  detectarTipoDispositivo() {
    const userAgent = navigator.userAgent.toLowerCase();
    
    if (userAgent.includes("mobile")) {
      return 1; // Mobile
    } else if (userAgent.includes("tablet") || 
              (userAgent.includes("ipad") || 
               userAgent.includes("android") && !userAgent.includes("mobile"))) {
      return 3; // Tablet
    } else if (userAgent.includes("cros") || 
              userAgent.includes("macintosh") || 
              userAgent.includes("windows") || 
              userAgent.includes("linux")) {
      return 2; // Desktop
    }
    
    return 0; // Desconhecido
  }

  /**
   * Obtém informações da conexão de rede atual
   * @returns {Object|null} Dados da conexão ou null se não disponível
   */
  obterInformacoesConexao() {
    if (!navigator.connection) {
      return null;
    }

    return {
      type: navigator.connection.effectiveType,
      downlink: navigator.connection.downlink,
      rtt: navigator.connection.rtt,
      saveData: navigator.connection.saveData || false,
      connectionType: navigator.connection.type || 'unknown'
    };
  }

  /**
   * Obtém informações da GPU (dados fixos)
   * @returns {Object} Dados da GPU (vendor e renderer)
   */
  obterInformacoesGPU() {
    try {
      const canvas = document.createElement('canvas');
      const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
      
      if (!gl) return { vendor: 'unknown', renderer: 'unknown' };
      
      const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
      if (!debugInfo) return { vendor: 'unknown', renderer: 'unknown' };
      
      return {
        vendor: gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL),
        renderer: gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL)
      };
    } catch (e) {
      return { vendor: 'unknown', renderer: 'unknown' };
    }
  }

  /**
   * Obtém ou gera fingerprint do dispositivo
   * @returns {string} Fingerprint único do dispositivo
   */
  obterFingerprint() {
    if (this.fingerprint) {
      return this.fingerprint;
    }

    // Verifica se já existe um fingerprint salvo
    const fingerprintSalvo = pegaLocal("fingerprint");
    if (fingerprintSalvo) {
      this.fingerprint = fingerprintSalvo;
      return this.fingerprint;
    }

    // Gera novo fingerprint
    this.fingerprint = this.gerarFingerprint();
    defineLocal("fingerprint", this.fingerprint);
    return this.fingerprint;
  }

  /**
   * Gera fingerprint único do dispositivo
   * @returns {string} Fingerprint gerado
   */
  gerarFingerprint() {
    const components = [
      navigator.userAgent,
      navigator.language,
      new Date().getTimezoneOffset(),
      screen.colorDepth,
      screen.width + 'x' + screen.height,
      navigator.hardwareConcurrency || '',
      navigator.deviceMemory || '',
      navigator.platform || '',
      window.devicePixelRatio || 1
    ];
    
    // Cria um hash simples baseado nos componentes
    let hash = 0;
    const str = components.join('###');
    
    for (let i = 0; i < str.length; i++) {
      const char = str.charCodeAt(i);
      hash = ((hash << 5) - hash) + char;
      hash = hash & hash; // Convert to 32bit integer
    }
    
    // Convertendo para string hexadecimal e adicionando timestamp para unicidade
    const fingerprint = Math.abs(hash).toString(16) + Date.now().toString(36);
    return fingerprint;
  }

  /**
   * Obtém informações de timezone (dados fixos)
   * @returns {Object} Dados do timezone
   */
  obterTimezone() {
    try {
      return {
        offset: new Date().getTimezoneOffset(),
        name: Intl.DateTimeFormat().resolvedOptions().timeZone
      };
    } catch (error) {
      return {
        offset: new Date().getTimezoneOffset(),
        name: 'unknown'
      };
    }
  }

  /**
   * Verifica se a geolocalização está habilitada
   * @returns {Promise<boolean>} True se habilitada
   */
  async verificarGeolocalizacaoHabilitada() {
    try {
      const configResponse = await configModulo("analytics", "configuracoes", "georeferencia", false);
      return !!configResponse;
    } catch (error) {
      return false;
    }
  }

  /**
   * Coleta dados de localização do GPS
   * @returns {Promise<Object>} Dados de coordenadas
   */
  coletarDadosLocalizacao() {
    return new Promise((resolve, reject) => {
      if (!navigator.geolocation) {
        reject(new Error('Geolocalização não suportada'));
        return;
      }

      navigator.geolocation.getCurrentPosition(
        (position) => {
          resolve({
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            accuracy: position.coords.accuracy,
            altitude: position.coords.altitude,
            altitudeAccuracy: position.coords.altitudeAccuracy,
            heading: position.coords.heading,
            speed: position.coords.speed,
            timestamp: position.timestamp
          });
        },
        (error) => reject(error),
        { 
          timeout: 10000, 
          maximumAge: 60000,
          enableHighAccuracy: false 
        }
      );
    });
  }

  /**
   * Obtém informações da bateria (dados do momento)
   * @returns {Promise<Object|null>} Dados da bateria ou null se não disponível
   */
  async obterInformacoesBateria() {
    try {
      if (!('getBattery' in navigator)) {
        return null;
      }

      const battery = await navigator.getBattery();
      return {
        level: Math.round(battery.level * 100),
        charging: battery.charging,
        chargingTime: battery.chargingTime,
        dischargingTime: battery.dischargingTime
      };
    } catch (error) {
      return null;
    }
  }

  /**
   * Verifica se o dispositivo é touch (dados fixos)
   * @returns {boolean} True se suporta touch
   */
  isTouchDevice() {
    return 'ontouchstart' in window || 
           navigator.maxTouchPoints > 0 || 
           navigator.msMaxTouchPoints > 0;
  }

  /**
   * Obtém informações de orientação da tela (dados do momento)
   * @returns {Object} Dados de orientação
   */
  obterOrientacaoTela() {
    return {
      angle: screen.orientation ? screen.orientation.angle : (screen.orientationAngle || 0),
      type: screen.orientation ? screen.orientation.type : 'unknown'
    };
  }

  /**
   * Obtém informações de memória atual (dados do momento)
   * @returns {Object|null} Dados de memória ou null se não disponível
   */
  obterMemoriaAtual() {
    if ('memory' in performance) {
      return {
        used: performance.memory.usedJSHeapSize,
        total: performance.memory.totalJSHeapSize,
        limit: performance.memory.jsHeapSizeLimit
      };
    }
    return null;
  }

  // ===== NOVOS MÉTODOS PARA DADOS FIXOS =====

  /**
   * Verifica se Java está habilitado
   * @returns {boolean} True se Java está disponível
   */
  verificarJavaEnabled() {
    try {
      return navigator.javaEnabled ? navigator.javaEnabled() : false;
    } catch (error) {
      return false;
    }
  }

  /**
   * Obtém capacidades de mídia
   * @returns {Object} Capacidades de mídia disponíveis
   */
  obterCapacidadesMedia() {
    const capabilities = {};
    
    if ('mediaCapabilities' in navigator) {
      capabilities.mediaCapabilities = true;
    }
    
    if ('mediaDevices' in navigator) {
      capabilities.mediaDevices = true;
    }
    
    if ('getUserMedia' in navigator) {
      capabilities.getUserMedia = true;
    }
    
    return capabilities;
  }

  /**
   * Obtém formatos de áudio suportados
   * @returns {Object} Formatos de áudio e seu suporte
   */
  obterFormatosAudio() {
    const audio = document.createElement('audio');
    const formatos = {};
    
    const tipos = [
      'audio/mpeg',
      'audio/ogg',
      'audio/mp4',
      'audio/wav',
      'audio/webm',
      'audio/aac'
    ];
    
    tipos.forEach(tipo => {
      const suporte = audio.canPlayType(tipo);
      if (suporte) {
        formatos[tipo] = suporte;
      }
    });
    
    return formatos;
  }

  /**
   * Obtém formatos de vídeo suportados
   * @returns {Object} Formatos de vídeo e seu suporte
   */
  obterFormatosVideo() {
    const video = document.createElement('video');
    const formatos = {};
    
    const tipos = [
      'video/mp4',
      'video/webm',
      'video/ogg',
      'video/avi',
      'video/mov'
    ];
    
    tipos.forEach(tipo => {
      const suporte = video.canPlayType(tipo);
      if (suporte) {
        formatos[tipo] = suporte;
      }
    });
    
    return formatos;
  }

  /**
   * Verifica APIs disponíveis
   * @returns {Object} Lista de APIs disponíveis
   */
  verificarAPIsDisponiveis() {
    const apis = {};
    
    // Lista de APIs para verificar
    const apisParaVerificar = [
      'fetch',
      'WebSocket',
      'localStorage',
      'sessionStorage',
      'indexedDB',
      'WebGL',
      'WebRTC',
      'ServiceWorker',
      'WebWorker',
      'Notification',
      'geolocation',
      'vibrate',
      'requestIdleCallback',
      'IntersectionObserver',
      'MutationObserver',
      'ResizeObserver'
    ];
    
    apisParaVerificar.forEach(api => {
      try {
        switch (api) {
          case 'fetch':
            apis[api] = typeof fetch !== 'undefined';
            break;
          case 'WebSocket':
            apis[api] = typeof WebSocket !== 'undefined';
            break;
          case 'localStorage':
            apis[api] = typeof Storage !== 'undefined' && 'localStorage' in window;
            break;
          case 'sessionStorage':
            apis[api] = typeof Storage !== 'undefined' && 'sessionStorage' in window;
            break;
          case 'indexedDB':
            apis[api] = 'indexedDB' in window;
            break;
          case 'WebGL':
            const canvas = document.createElement('canvas');
            apis[api] = !!(canvas.getContext('webgl') || canvas.getContext('experimental-webgl'));
            break;
          case 'geolocation':
            apis[api] = 'geolocation' in navigator;
            break;
          case 'vibrate':
            apis[api] = 'vibrate' in navigator;
            break;
          default:
            apis[api] = api in window;
        }
      } catch (error) {
        apis[api] = false;
      }
    });
    
    return apis;
  }

  /**
   * Verifica se está em modo escuro
   * @returns {boolean} True se modo escuro está ativo
   */
  verificarModoEscuro() {
    if (window.matchMedia) {
      return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    return false;
  }

  /**
   * Verifica se movimento reduzido está ativo
   * @returns {boolean} True se prefere movimento reduzido
   */
  verificarMovimentoReduzido() {
    if (window.matchMedia) {
      return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }
    return false;
  }

  /**
   * Verifica se alto contraste está ativo
   * @returns {boolean} True se prefere alto contraste
   */
  verificarAltoContraste() {
    if (window.matchMedia) {
      return window.matchMedia('(prefers-contrast: high)').matches;
    }
    return false;
  }

  /**
   * Obtém amostra de fontes disponíveis
   * @returns {Array} Lista de fontes detectadas
   */
  obterFontesDisponiveis() {
    const fontesComuns = [
      'Arial', 'Helvetica', 'Times New Roman', 'Times', 'Courier New', 'Courier',
      'Verdana', 'Georgia', 'Palatino', 'Garamond', 'Bookman', 'Comic Sans MS',
      'Trebuchet MS', 'Arial Black', 'Impact', 'Liberation Sans', 'Tahoma'
    ];
    
    const fontesDetectadas = [];
    const testString = 'mmmmmmmmmmlli';
    const testSize = '72px';
    const basefonts = ['monospace', 'sans-serif', 'serif'];
    
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    
    // Função para obter largura do texto
    function obterLargura(font) {
      context.font = testSize + ' ' + font;
      return context.measureText(testString).width;
    }
    
    // Medidas base
    const baselines = {};
    basefonts.forEach(basefont => {
      baselines[basefont] = obterLargura(basefont);
    });
    
    // Testa cada fonte
    fontesComuns.forEach(fonte => {
      let detectada = false;
      basefonts.forEach(basefont => {
        if (obterLargura(fonte + ',' + basefont) !== baselines[basefont]) {
          detectada = true;
        }
      });
      if (detectada) {
        fontesDetectadas.push(fonte);
      }
    });
    
    return fontesDetectadas;
  }

  /**
   * Obtém canvas fingerprint
   * @returns {string} Hash do canvas fingerprint
   */
  obterCanvasFingerprint() {
    try {
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      
      // Desenha texto e formas para criar fingerprint único
      ctx.textBaseline = 'top';
      ctx.font = '14px Arial';
      ctx.fillStyle = '#f60';
      ctx.fillRect(125, 1, 62, 20);
      ctx.fillStyle = '#069';
      ctx.fillText('Canvas fingerprint', 2, 15);
      ctx.fillStyle = 'rgba(102, 204, 0, 0.7)';
      ctx.fillText('Analytics tracking', 4, 45);
      
      return this.simpleHash(canvas.toDataURL());
    } catch (error) {
      return 'canvas_error';
    }
  }

  /**
   * Obtém WebGL fingerprint mais detalhado
   * @returns {Object} Dados detalhados do WebGL
   */
  obterWebGLFingerprint() {
    try {
      const canvas = document.createElement('canvas');
      const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
      
      if (!gl) return { error: 'webgl_not_supported' };
      
      const result = {};
      
      // Informações básicas
      result.version = gl.getParameter(gl.VERSION);
      result.shadingLanguageVersion = gl.getParameter(gl.SHADING_LANGUAGE_VERSION);
      result.vendor = gl.getParameter(gl.VENDOR);
      result.renderer = gl.getParameter(gl.RENDERER);
      
      // Debug info se disponível
      const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
      if (debugInfo) {
        result.unmaskedVendor = gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL);
        result.unmaskedRenderer = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
      }
      
      // Extensões
      result.extensions = gl.getSupportedExtensions();
      
      // Parâmetros
      result.maxTextureSize = gl.getParameter(gl.MAX_TEXTURE_SIZE);
      result.maxViewportDims = gl.getParameter(gl.MAX_VIEWPORT_DIMS);
      result.maxVertexAttribs = gl.getParameter(gl.MAX_VERTEX_ATTRIBS);
      
      return result;
    } catch (error) {
      return { error: 'webgl_error' };
    }
  }

  /**
   * Obtém plugins instalados (limitado por segurança)
   * @returns {Array} Lista de plugins detectados
   */
  obterPlugins() {
    const plugins = [];
    
    if (navigator.plugins) {
      for (let i = 0; i < navigator.plugins.length; i++) {
        const plugin = navigator.plugins[i];
        plugins.push({
          name: plugin.name,
          description: plugin.description,
          filename: plugin.filename,
          length: plugin.length
        });
      }
    }
    
    return plugins;
  }

  // ===== NOVOS MÉTODOS PARA DADOS DO MOMENTO =====

  /**
   * Obtém timing de performance
   * @returns {Object|null} Dados de performance timing
   */
  obterPerformanceTiming() {
    if (!performance || !performance.timing) {
      return null;
    }
    
    const timing = performance.timing;
    return {
      navigationStart: timing.navigationStart,
      loadEventEnd: timing.loadEventEnd,
      domComplete: timing.domComplete,
      domContentLoaded: timing.domContentLoadedEventEnd - timing.navigationStart,
      loadComplete: timing.loadEventEnd - timing.navigationStart
    };
  }

  /**
   * Obtém informações de navegação
   * @returns {Object|null} Dados de navegação
   */
  obterNavigationTiming() {
    if (!performance || !performance.navigation) {
      return null;
    }
    
    return {
      type: performance.navigation.type,
      redirectCount: performance.navigation.redirectCount
    };
  }

  /**
   * Obtém tempo de inatividade (aproximado)
   * @returns {number} Tempo desde última atividade em ms
   */
  obterTempoInatividade() {
    if (this.ultimaAtividade) {
      return Date.now() - this.ultimaAtividade;
    }
    return 0;
  }

  /**
   * Atualiza timestamp da última atividade
   */
  atualizarUltimaAtividade() {
    this.ultimaAtividade = Date.now();
  }

  /**
   * Obtém dados de sensores (se disponíveis)
   * @returns {Promise<Object>} Dados dos sensores
   */
  async obterDadosSensores() {
    const sensores = {};
    
    // Acelerômetro e giroscópio (apenas se permitido)
    if ('DeviceMotionEvent' in window) {
      sensores.deviceMotionSupported = true;
    }
    
    if ('DeviceOrientationEvent' in window) {
      sensores.deviceOrientationSupported = true;
    }
    
    // Sensor de luz ambiente
    if ('AmbientLightSensor' in window) {
      try {
        sensores.ambientLightSupported = true;
      } catch (error) {
        sensores.ambientLightSupported = false;
      }
    }
    
    return sensores;
  }

  /**
   * Função auxiliar para criar hash simples
   * @param {string} str String para fazer hash
   * @returns {string} Hash da string
   */
  simpleHash(str) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
      const char = str.charCodeAt(i);
      hash = ((hash << 5) - hash) + char;
      hash = hash & hash;
    }
    return hash.toString(36);
  }

  /**
   * Limpa cache de dados específicos
   * @param {string} tipo - 'fixos', 'localizacao', 'externos', 'momento' ou 'todos'
   */
  limparCache(tipo = 'todos') {
    switch (tipo) {
      case 'fixos':
        this.dadosFixos = null;
        break;
      case 'localizacao':
        this.dadosLocalizacao = null;
        break;
      case 'externos':
        this.dadosExternos = null;
        break;
      case 'momento':
        this.dadosMomento = null;
        break;
      case 'todos':
        this.dadosFixos = null;
        this.dadosLocalizacao = null;
        this.dadosExternos = null;
        this.dadosMomento = null;
        this.fingerprint = null;
        break;
    }
  }

  /**
   * Força nova coleta de dados de localização
   */
  async atualizarLocalizacao() {
    this.dadosLocalizacao = null;
    return await this.obterDadosLocalizacao();
  }

  /**
   * Força nova coleta de dados externos
   */
  async atualizarDadosExternos() {
    this.dadosExternos = null;
    return await this.obterDadosExternos();
  }

  /**
   * Inicializa listeners para tracking de atividade
   */
  inicializarTrackingAtividade() {
    const eventos = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
    
    eventos.forEach(evento => {
      document.addEventListener(evento, () => {
        this.atualizarUltimaAtividade();
      }, { passive: true });
    });
  }

  /**
   * Obtém resumo de dados para envio rápido
   * @returns {Promise<Object>} Dados essenciais em formato compacto
   */
  async obterResumo() {
    const fixos = await this.obterDadosFixos();
    const momento = await this.obterDadosMomento();
    
    return {
      // Dados essenciais sempre enviados
      fingerprint: fixos.fingerprint,
      dispositivo: fixos.tipoDispositivo,
      navegador: fixos.userAgent.split(' ')[0],
      tela: `${fixos.screenWidth}x${fixos.screenHeight}`,
      idioma: fixos.language,
      timezone: fixos.timezone.name,
      
      // Dados do momento atual
      url: momento.url,
      timestamp: momento.timestamp,
      online: momento.online,
      visivel: momento.documentVisible,
      
      // Dados de conexão se disponível
      conexao: momento.conexao?.type || null,
      bateria: momento.bateria?.level || null
    };
  }
}