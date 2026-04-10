<?php

class CNPJConsultor {
    
    private $baseUrl = "https://casadosdados.com.br/solucao/cnpj/";
    private $timeout = 30;
    
    /**
     * Consulta dados de uma empresa pelo CNPJ
     * 
     * @param string $cnpj CNPJ da empresa (com ou sem formatação)
     * @return array Dados da empresa ou erro
     */
    public function consultar($cnpj) {
        try {
       
            // Faz a requisição
            $html = $this->fazerRequisicao($cnpj);
            
            if (!$html) {
                return [
                    'erro' => true,
                    'mensagem' => 'Não foi possível obter dados do servidor'
                ];
            }
            
            // Extrai os dados
            $dados = $this->extrairDadosEmpresa($html);
            
            return $dados;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Faz a requisição HTTP para o site
     * 
     * @param string $cnpj CNPJ limpo
     * @return string|false HTML da página ou false em caso de erro
     */
    private function fazerRequisicao($cnpj) {
        $url = $this->baseUrl . $cnpj . "#google_vignette";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $conteudo = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $erro = curl_error($ch);
            curl_close($ch);
            throw new Exception('Erro cURL: ' . $erro);
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception('Erro HTTP: ' . $httpCode);
        }
        
        return $conteudo;
    }
    
    /**
     * Extrai dados da empresa do HTML
     * 
     * @param string $html HTML da página
     * @return array Dados extraídos
     */
    private function extrairDadosEmpresa($html) {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        
        // Melhor tratamento de encoding
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dom->loadHTML($html);
        libxml_clear_errors();
        
        $xpath = new DOMXPath($dom);
        
        // Função para extrair por label
        $extrairPorLabel = function($xpath, $label) {
            // Primeiro, tenta o padrão mais específico
            $query1 = "//label[contains(text(), '$label')]/following-sibling::p[@class='has-text-weight-bold']";
            $nodes = $xpath->query($query1);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
            
            // Segundo, tenta buscar na mesma div
            $query2 = "//label[contains(text(), '$label')]/parent::div//p[@class='has-text-weight-bold']";
            $nodes = $xpath->query($query2);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
            
            // Terceiro, tenta sem a classe específica
            $query3 = "//label[contains(text(), '$label')]/following-sibling::p";
            $nodes = $xpath->query($query3);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
            
            // Quarto, busca na div pai
            $query4 = "//label[contains(text(), '$label')]/parent::div//p";
            $nodes = $xpath->query($query4);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
            
            return null;
        };
        
        // Função para extrair dados em sub-divs
        $extrairPorLabelDiv = function($xpath, $label) {
            $query = "//div[.//label[contains(text(), '$label')]]//p[@class='has-text-weight-bold']";
            $nodes = $xpath->query($query);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
            
            $query2 = "//div[.//label[contains(text(), '$label')]]//p";
            $nodes = $xpath->query($query2);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
            
            return null;
        };
        
        // Extrair informações básicas
        $dadosEmpresa = [
            'ultima_atualizacao' => $extrairPorLabel($xpath, 'Ultima Atualização'),
            'cnpj' => $extrairPorLabel($xpath, 'CNPJ:'),
            'razao_social' => $extrairPorLabel($xpath, 'Razão Social:'),
            'nome_fantasia' => $extrairPorLabel($xpath, 'Nome Fantasia:'),
            'situacao_cadastral' => [
                'situacao' => $extrairPorLabel($xpath, 'Situação Cadastral:'),
                'data_situacao' => $extrairPorLabelDiv($xpath, 'Data da Situação:'),
                'motivo_situacao' => $extrairPorLabelDiv($xpath, 'Motivo da Situação:')
            ],
            'data_abertura' => $extrairPorLabel($xpath, 'Data de Abertura:'),
            'matriz_filial' => $extrairPorLabel($xpath, 'Matriz ou Filial:'),
            'natureza_juridica' => $extrairPorLabel($xpath, 'Natureza Jurídica:'),
            'mei' => [
                'eh_mei' => $extrairPorLabel($xpath, 'Empresa MEI:'),
                'data_opcao_mei' => $extrairPorLabelDiv($xpath, 'Data de Opção pelo MEI:'),
                'data_exclusao_mei' => $extrairPorLabelDiv($xpath, 'Data de Exclusão MEI:')
            ],
            'capital_social' => $extrairPorLabel($xpath, 'Capital Social:'),
            'endereco' => [
                'logradouro' => $extrairPorLabel($xpath, 'Logradouro:'),
                'numero' => $extrairPorLabel($xpath, 'Número:'),
                'complemento' => $extrairPorLabel($xpath, 'Complemento:'),
                'bairro' => $extrairPorLabel($xpath, 'Bairro:'),
                'cep' => $extrairPorLabel($xpath, 'CEP:'),
                'municipio' => null,
                'estado' => null
            ],
            'contato' => [
                'email' => null,
                'telefones' => []
            ],
            'atividades' => [
                'cnae_principal' => $extrairPorLabel($xpath, 'CNAE Principal:'),
                'cnaes_secundarios' => []
            ],
            'simples' => [
                'optante' => $extrairPorLabel($xpath, 'Simples:'),
                'data_opcao_simples' => $extrairPorLabelDiv($xpath, 'Data de Opção pelo Simples:'),
                'data_exclusao_simples' => $extrairPorLabelDiv($xpath, 'Data de Exclusão Simples:')
            ],
            'socios' => []
        ];
        
        // Extrair município e estado
        $municipioNodes = $xpath->query("//label[contains(text(), 'Municipio:')]/parent::div//a");
        if ($municipioNodes->length == 0) {
            $municipioNodes = $xpath->query("//label[contains(text(), 'Municipio:')]/following-sibling::p//a");
        }
        if ($municipioNodes->length > 0) {
            $municipioText = trim($municipioNodes->item(0)->textContent);
            $dadosEmpresa['endereco']['municipio'] = trim(preg_replace('/\s+/', ' ', $municipioText));
        }
        
        $estadoNodes = $xpath->query("//label[contains(text(), 'Estado:')]/parent::div//a");
        if ($estadoNodes->length == 0) {
            $estadoNodes = $xpath->query("//label[contains(text(), 'Estado:')]/following-sibling::p//a");
        }
        if ($estadoNodes->length > 0) {
            $estadoText = trim($estadoNodes->item(0)->textContent);
            $dadosEmpresa['endereco']['estado'] = trim(preg_replace('/\s+/', ' ', $estadoText));
        }
        
        // Extrair email
        $emailNodes = $xpath->query("//a[starts-with(@href, 'mailto:')]");
        if ($emailNodes->length > 0) {
            $dadosEmpresa['contato']['email'] = trim($emailNodes->item(0)->textContent);
        }
        
        // Extrair telefones
        $telefoneNodes = $xpath->query("//a[starts-with(@href, 'tel:')]");
        foreach ($telefoneNodes as $telefoneNode) {
            $telefone = trim($telefoneNode->textContent);
            if (!empty($telefone)) {
                $dadosEmpresa['contato']['telefones'][] = $telefone;
            }
        }
        
        // Extrair CNAEs secundários
        $cnaeSecDiv = $xpath->query("//label[contains(text(), 'CNAEs Secundários:')]/parent::div");
        if ($cnaeSecDiv->length > 0) {
            $cnaeNodes = $xpath->query(".//p[@class='has-text-weight-bold']", $cnaeSecDiv->item(0));
            foreach ($cnaeNodes as $cnaeNode) {
                $cnaeText = trim($cnaeNode->textContent);
                if (!empty($cnaeText)) {
                    $dadosEmpresa['atividades']['cnaes_secundarios'][] = $cnaeText;
                }
            }
        }
        
        // Extrair sócios
        $sociosDiv = $xpath->query("//label[contains(text(), 'Sócios:')]/parent::div");
        if ($sociosDiv->length > 0) {
            $socioNodes = $xpath->query(".//p[@class='has-text-weight-bold']", $sociosDiv->item(0));
            foreach ($socioNodes as $socioNode) {
                $socioText = trim($socioNode->textContent);
                if (!empty($socioText) && strpos($socioText, ' - ') !== false) {
                    $partes = explode(' - ', $socioText);
                    if (count($partes) >= 3) {
                        $dadosEmpresa['socios'][] = [
                            'nome' => trim($partes[0]),
                            'qualificacao' => trim($partes[1]),
                            'data_entrada' => trim($partes[2])
                        ];
                    }
                }
            }
        }
        
        return $dadosEmpresa;
    }
    
    /**
     * Define timeout para requisições
     * 
     * @param int $timeout Timeout em segundos
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }
    
    /**
     * Retorna apenas dados básicos da empresa
     * 
     * @param string $cnpj CNPJ da empresa
     * @return array Dados básicos ou erro
     */
    public function consultarBasico($cnpj) {
        $resultado = $this->consultar($cnpj);
        
        if ($resultado['erro']) {
            return $resultado;
        }
        
        $dados = $resultado['dados'];
        
        return [
            'erro' => false,
            'dados' => [
                'cnpj' => $dados['cnpj'],
                'razao_social' => $dados['razao_social'],
                'nome_fantasia' => $dados['nome_fantasia'],
                'situacao_cadastral' => $dados['situacao_cadastral']['situacao'],
                'data_abertura' => $dados['data_abertura'],
                'matriz_filial' => $dados['matriz_filial']
            ]
        ];
    }
}

?>