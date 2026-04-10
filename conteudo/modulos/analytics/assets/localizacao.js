class Localizacao {
    constructor(options = {}) {
        this.ip = null;
        this.gpsData = null;
        this.config = {
            ativarIP: options.ativarIP !== false, // por padrão está ativo
            ativarGPS: options.ativarGPS !== false, // por padrão está ativo
            monitoramentoAtivo: options.monitoramentoAtivo !== false
        };
        this.watchId = null; // para controle do monitoramento GPS
    }

    // Múltiplos provedores de IP com fallback
    async getIpManual() {
        if (!this.config.ativarIP) {
            throw new Error('Monitoramento de IP está desativado');
        }

        const providers = [
             {
                url: 'https://get.geojs.io/v1/ip/geo.json',
                transformer: (data) => ({
                    empresa: data.organization,
                    cidade: data.city,
                    pais: data.country,
                    ip: data.ip,
                    latitude: parseFloat(data.latitude),
                    longitude: parseFloat(data.longitude),
                    estado: data.region
                })
            },
            {
                url: 'https://free.freeipapi.com/api/json/',
                transformer: (data) => ({
                    empresa: data.asnOrganization,
                    cidade: data.cityName,
                    pais: data.countryName,
                    ip: data.ipAddress,
                    latitude: data.latitude,
                    longitude: data.longitude,
                    estado: data.regionName
                })
            }
        ];

        for (const provider of providers) {
            try {

                const response = await fetch(provider.url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }

                const data = await response.json();
                const transformedData = provider.transformer(data);
                
                return transformedData;

            } catch (error) {
                console.warn(`Falha ao buscar dados de ${provider.url}:`, error.message);
                // Continua para o próximo provedor
            }
        }

        throw new Error('Não foi possível obter dados de IP de nenhum provedor');
    }

    // GPS sem cache
    async gps() {
        if (!this.config.ativarGPS) {
            throw new Error('Monitoramento de GPS está desativado');
        }

        if (!navigator.geolocation) {
            throw new Error('Geolocalização não suportada');
        }
        
        
            
          
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                async (position) => {
                   // let parse = await this.parseAdress(position.coords.latitude, position.coords.longitude);
                    
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
                (error) => {
                    console.error('Erro GPS:', error);
                    reject(error);
                },
                { 
                    timeout: 15000, 
                    maximumAge: 60000,
                    enableHighAccuracy: true 
                }
            );
        });
    }

    // Método assíncrono para obter informações de IP
    async info() {
        try {
            if (!this.ip) {
                this.ip = await this.getIpManual();
            }
            return this.ip;
        } catch (error) {
            console.error('Erro ao obter informações de IP:', error);
            throw error;
        }
    }

    // Método para obter todas as informações (IP + GPS)
    async getTodasInformacoes() {
        if (!this.config.monitoramentoAtivo) {
            throw new Error('Monitoramento de localização está desativado');
        }

        const resultados = {};
        
        if (this.config.ativarIP) {
            try {
                resultados.ip = await this.info();
            } catch (error) {
                console.error('Erro ao obter dados de IP:', error);
                resultados.ip = null;
            }
        }

        if (this.config.ativarGPS) {
            try {
                resultados.gps = await this.gps();
            } catch (error) {
                console.error('Erro ao obter dados GPS:', error);
                resultados.gps = null;
            }
        }

        return resultados;
    }

    // Métodos para controle do monitoramento
    ativarMonitoramento() {
        this.config.monitoramentoAtivo = true;
        console.log('Monitoramento de localização ativado');
    }

    desativarMonitoramento() {
        this.config.monitoramentoAtivo = false;
        this.pararMonitoramentoGPS();
        console.log('Monitoramento de localização desativado');
    }

    ativarIP() {
        this.config.ativarIP = true;
        console.log('Monitoramento de IP ativado');
    }

    desativarIP() {
        this.config.ativarIP = false;
        this.ip = null; // limpa dados cached
        console.log('Monitoramento de IP desativado');
    }

    ativarGPS() {
        this.config.ativarGPS = true;
        console.log('Monitoramento de GPS ativado');
    }

    desativarGPS() {
        this.config.ativarGPS = false;
        this.pararMonitoramentoGPS();
        this.gpsData = null; // limpa dados cached
        console.log('Monitoramento de GPS desativado');
    }

    // Monitoramento contínuo de GPS
    iniciarMonitoramentoGPS(callback, opcoes = {}) {
        if (!this.config.ativarGPS || !this.config.monitoramentoAtivo) {
            throw new Error('GPS ou monitoramento geral está desativado');
        }

        if (!navigator.geolocation) {
            throw new Error('Geolocalização não suportada');
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 5000,
            ...opcoes
        };

        this.watchId = navigator.geolocation.watchPosition(
            (position) => {
                this.gpsData = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    altitudeAccuracy: position.coords.altitudeAccuracy,
                    heading: position.coords.heading,
                    speed: position.coords.speed,
                    timestamp: position.timestamp,
                    parse: this.parseAdress(position.coords.latitude, position.coords.longitude)
                };
                callback(this.gpsData);
            },
            (error) => {
                console.error('Erro no monitoramento GPS:', error);
                callback(null, error);
            },
            options
        );

        console.log('Monitoramento contínuo de GPS iniciado');
        return this.watchId;
    }
    
async parseAdress(lat, long) {
    const nominatimUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${long}`;
    const proxyUrl = `https://api.allorigins.win/get?url=${encodeURIComponent(nominatimUrl)}`;
    
    try {
        const response = await fetch(proxyUrl);
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        
        const result = await response.json();
        const data = JSON.parse(result.contents);
        return data;
    } catch (error) {

        throw error;
    }
}

    pararMonitoramentoGPS() {
        if (this.watchId !== null) {
            navigator.geolocation.clearWatch(this.watchId);
            this.watchId = null;
            console.log('Monitoramento contínuo de GPS parado');
        }
    }

    // Obter status atual das configurações
    getStatus() {
        return {
            configuracoes: { ...this.config },
            dados: {
                ip: this.ip ? 'carregado' : 'não carregado',
                gps: this.gpsData ? 'carregado' : 'não carregado'
            },
            monitoramentoGPS: this.watchId !== null ? 'ativo' : 'inativo'
        };
    }

    // Método para resetar todas as configurações
    resetar() {
        this.pararMonitoramentoGPS();
        this.config = {
            ativarIP: true,
            ativarGPS: true,
            monitoramentoAtivo: true
        };
        this.ip = null;
        this.gpsData = null;
        console.log('Configurações resetadas para padrão');
    }
}