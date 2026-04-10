
    <div class="container-fluid dashboard-container">
        <!-- Date filter -->
        <div class="date-filter">
            <label for="dateRange">Período de Análise:</label>
            <select id="dateRange" class="form-select">
                <option>Últimos 7 dias</option>
                <option>Últimos 30 dias</option>
                <option>Este mês</option>
                <option>Mês passado</option>
                <option>Este ano</option>
                <option>Personalizado</option>
            </select>
            <button class="btn btn-filter ms-auto"><i class="bi bi-funnel-fill me-2"></i>Aplicar Filtro</button>
        </div>
        
        <!-- Stats cards -->
        <div class="row g-3">
            <div class="col-md-6 col-xl-3">
                <div class="card stats-card users-card">
                    <i class="bi bi-person-fill-add"></i>
                    <h3>4,582</h3>
                    <p>Usuários Totais</p>
                    <span class="performance-indicator up">
                        <i class="bi bi-graph-up-arrow"></i> 12.5%
                    </span>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stats-card logged-card">
                    <i class="bi bi-person-check-fill"></i>
                    <h3>2,845</h3>
                    <p>Usuários Logados</p>
                    <span class="performance-indicator up">
                        <i class="bi bi-graph-up-arrow"></i> 8.3%
                    </span>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stats-card views-card">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <h3>12,547</h3>
                    <p>Visualizações de Página</p>
                    <span class="performance-indicator up">
                        <i class="bi bi-graph-up-arrow"></i> 23.7%
                    </span>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stats-card time-card">
                    <i class="bi bi-stopwatch-fill"></i>
                    <h3>2m 45s</h3>
                    <p>Tempo Médio na Página</p>
                    <span class="performance-indicator down">
                        <i class="bi bi-graph-down-arrow"></i> 3.2%
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Charts -->
        <div class="row g-3 mt-1">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <span class="section-title">Usuários Logados vs. Não Logados</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline dropdown-toggle" type="button" id="chartOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartOptions">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-download me-2"></i>Exportar CSV</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-image me-2"></i>Salvar Imagem</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-fullscreen me-2"></i>Ver em Tela Cheia</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <span class="section-title">Visualizações de Página</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline dropdown-toggle" type="button" id="pageViewsOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="pageViewsOptions">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-download me-2"></i>Exportar CSV</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-image me-2"></i>Salvar Imagem</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-fullscreen me-2"></i>Ver em Tela Cheia</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="pageViewsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <span class="section-title">Dispositivos</span>
                        <button class="btn btn-sm btn-outline">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="devicesChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Gráfico geográfico com mapa -->
                <div class="card">
                    <div class="card-header">
                        <span class="section-title">Tráfego por Região</span>
                        <button class="btn btn-sm btn-outline">
                            <i class="bi bi-globe-americas"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="geo-map-container" style="height: 350px; position: relative;">
                            <div id="mapChart" class="w-100 h-100"></div>
                        </div>
                        <div class="map-legend mt-2 d-flex justify-content-center">
                            <div class="d-flex align-items-center me-3">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #4f46e5; margin-right: 5px;"></div>
                                <small>&lt; 3.000</small>
                            </div>
                            <div class="d-flex align-items-center me-3">
                                <div style="width: 15px; height: 15px; border-radius: 50%; background-color: #6366f1; margin-right: 5px;"></div>
                                <small>3.000 - 5.000</small>
                            </div>
                            <div class="d-flex align-items-center me-3">
                                <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #818cf8; margin-right: 5px;"></div>
                                <small>5.000 - 10.000</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div style="width: 25px; height: 25px; border-radius: 50%; background-color: #10b981; margin-right: 5px;"></div>
                                <small>&gt; 10.000</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <span class="section-title">Atividade Recente</span>
                        <button class="btn btn-sm btn-outline">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="latest-activity-item">
                            <h6>Novo Usuário Registrado</h6>
                            <p>Usuário completou o registro e confirmou conta</p>
                            <p class="latest-activity-time">
                                <i class="bi bi-clock-fill"></i> Há 5 minutos
                            </p>
                        </div>
                        <div class="latest-activity-item">
                            <h6>Pico de Tráfego</h6>
                            <p>Aumento de 250% no tráfego na página "Produtos"</p>
                            <p class="latest-activity-time">
                                <i class="bi bi-clock-fill"></i> Há 30 minutos
                            </p>
                        </div>
                        <div class="latest-activity-item">
                            <h6>Nova Postagem Publicada</h6>
                            <p>Artigo "Como melhorar SEO" foi publicado</p>
                            <p class="latest-activity-time">
                                <i class="bi bi-clock-fill"></i> Há 2 horas
                            </p>
                        </div>
                        <div class="latest-activity-item">
                            <h6>Usuário Premium Inscrito</h6>
                            <p>Novo usuário adquiriu o plano Premium</p>
                            <p class="latest-activity-time">
                                <i class="bi bi-clock-fill"></i> Há 4 horas
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content table -->
        <div class="card">
            <div class="card-header">
                <span class="section-title">Páginas Mais Visitadas</span>
                <button class="btn btn-outline">
                    <i class="bi bi-file-earmark-bar-graph-fill me-1"></i> Ver Relatório Completo
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Página</th>
                                <th>Visualizações</th>
                                <th>Usuários</th>
                                <th>Usuários Logados</th>
                                <th>Tempo Médio</th>
                                <th>Taxa de Saída</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="page-name">/inicio</td>
                                <td><span class="highlight">5,234</span></td>
                                <td>3,642</td>
                                <td>2,455 (67.4%)</td>
                                <td>1m 45s</td>
                                <td>24.3%</td>
                            </tr>
                            <tr>
                                <td class="page-name">/produtos</td>
                                <td><span class="highlight">3,845</span></td>
                                <td>2,743</td>
                                <td>2,125 (77.5%)</td>
                                <td>3m 12s</td>
                                <td>18.7%</td>
                            </tr>
                            <tr>
                                <td class="page-name">/sobre</td>
                                <td><span class="highlight">2,683</span></td>
                                <td>1,945</td>
                                <td>982 (50.5%)</td>
                                <td>2m 35s</td>
                                <td>32.1%</td>
                            </tr>
                            <tr>
                                <td class="page-name">/blog</td>
                                <td><span class="highlight">2,456</span></td>
                                <td>1,834</td>
                                <td>1,243 (67.8%)</td>
                                <td>4m 23s</td>
                                <td>15.4%</td>
                            </tr>
                            <tr>
                                <td class="page-name">/contato</td>
                                <td><span class="highlight">1,832</span></td>
                                <td>1,634</td>
                                <td>954 (58.4%)</td>
                                <td>1m 58s</td>
                                <td>43.7%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <!-- Tippy.js for tooltips -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.7/tippy.umd.min.js"></script>
    <!-- jVectorMap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery.jvectormap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery-jvectormap.min.js"></script>
    
    <script>
        // Charts initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Users Chart with gradient
            const usersCtx = document.getElementById('usersChart').getContext('2d');
            
            // Create gradients
            const loggedGradient = usersCtx.createLinearGradient(0, 0, 0, 400);
            loggedGradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
            loggedGradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');
            
            const nonLoggedGradient = usersCtx.createLinearGradient(0, 0, 0, 400);
            nonLoggedGradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
            nonLoggedGradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
            
            const usersChart = new Chart(usersCtx, {
                type: 'line',
                data: {
                    labels: ['01 Mar', '02 Mar', '03 Mar', '04 Mar', '05 Mar', '06 Mar', '07 Mar'],
                    datasets: [
                        {
                            label: 'Usuários Logados',
                            data: [325, 342, 365, 352, 410, 450, 423],
                            borderColor: '#4f46e5',
                            backgroundColor: loggedGradient,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#4f46e5',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Usuários Não Logados',
                            data: [245, 256, 268, 254, 280, 295, 302],
                            borderColor: '#10b981',
                            backgroundColor: nonLoggedGradient,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            cornerRadius: 8,
                            boxPadding: 6,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6b7280'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6b7280'
                            }
                        }
                    }
                }
            });
            
            // Page Views Chart
            const pageViewsCtx = document.getElementById('pageViewsChart').getContext('2d');
            const pageViewsGradient = pageViewsCtx.createLinearGradient(0, 0, 0, 400);
            pageViewsGradient.addColorStop(0, '#6366f1');
            pageViewsGradient.addColorStop(1, '#818cf8');
            
            const pageViewsChart = new Chart(pageViewsCtx, {
                type: 'bar',
                data: {
                    labels: ['01 Mar', '02 Mar', '03 Mar', '04 Mar', '05 Mar', '06 Mar', '07 Mar'],
                    datasets: [
                        {
                            label: 'Visualizações de Página',
                            data: [1562, 1495, 1845, 1702, 2105, 2340, 2240],
                            backgroundColor: pageViewsGradient,
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            cornerRadius: 8,
                            boxPadding: 6
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6b7280'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6b7280'
                            }
                        }
                    }
                }
            });
            
            // Devices Chart
            const devicesCtx = document.getElementById('devicesChart').getContext('2d');
            const devicesChart = new Chart(devicesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Desktop', 'Mobile', 'Tablet'],
                    datasets: [
                        {
                            data: [45, 40, 15],
                            backgroundColor: [
                                '#4f46e5',
                                '#10b981',
                                '#f59e0b'
                            ],
                            borderWidth: 0,
                            hoverOffset: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            cornerRadius: 8,
                            boxPadding: 6
                        }
                    },
                    cutout: '70%'
                }
            });
            
            // Gráfico de Tráfego por Região - Mapa com Bolhas
            // Criamos um mapa SVG do Brasil
            const createBrazilMap = () => {
                // Dados de acesso por estado
                const regionData = [
                    { name: 'São Paulo', region: 'Sudeste', visits: 8500, lat: -23.5505, lng: -46.6333, radius: 25 },
                    { name: 'Rio de Janeiro', region: 'Sudeste', visits: 4000, lat: -22.9068, lng: -43.1729, radius: 18 },
                    { name: 'Minas Gerais', region: 'Sudeste', visits: 3200, lat: -19.9167, lng: -43.9345, radius: 16 },
                    { name: 'Bahia', region: 'Nordeste', visits: 2800, lat: -12.9714, lng: -38.5014, radius: 15 },
                    { name: 'Rio Grande do Sul', region: 'Sul', visits: 2600, lat: -30.0277, lng: -51.2287, radius: 14 },
                    { name: 'Paraná', region: 'Sul', visits: 2300, lat: -25.4195, lng: -49.2646, radius: 14 },
                    { name: 'Pernambuco', region: 'Nordeste', visits: 1900, lat: -8.0476, lng: -34.8770, radius: 12 },
                    { name: 'Ceará', region: 'Nordeste', visits: 1750, lat: -3.7172, lng: -38.5433, radius: 12 },
                    { name: 'Distrito Federal', region: 'Centro-Oeste', visits: 1600, lat: -15.7801, lng: -47.9292, radius: 11 },
                    { name: 'Goiás', region: 'Centro-Oeste', visits: 1450, lat: -16.6799, lng: -49.2550, radius: 10 },
                    { name: 'Santa Catarina', region: 'Sul', visits: 1350, lat: -27.5954, lng: -48.5480, radius: 10 },
                    { name: 'Pará', region: 'Norte', visits: 1200, lat: -1.4558, lng: -48.4902, radius: 9 },
                    { name: 'Amazonas', region: 'Norte', visits: 950, lat: -3.1190, lng: -60.0217, radius: 8 },
                    { name: 'Paraíba', region: 'Nordeste', visits: 850, lat: -7.1219, lng: -34.8829, radius: 7 },
                    { name: 'Mato Grosso', region: 'Centro-Oeste', visits: 780, lat: -15.6014, lng: -56.0979, radius: 7 },
                    { name: 'Rio Grande do Norte', region: 'Nordeste', visits: 720, lat: -5.7945, lng: -35.2120, radius: 7 },
                    { name: 'Espírito Santo', region: 'Sudeste', visits: 680, lat: -20.2976, lng: -40.2959, radius: 6 },
                    { name: 'Alagoas', region: 'Nordeste', visits: 620, lat: -9.6498, lng: -35.7089, radius: 6 },
                    { name: 'Piauí', region: 'Nordeste', visits: 580, lat: -5.0892, lng: -42.8019, radius: 6 },
                    { name: 'Mato Grosso do Sul', region: 'Centro-Oeste', visits: 550, lat: -20.4697, lng: -54.6201, radius: 6 },
                    { name: 'Sergipe', region: 'Nordeste', visits: 480, lat: -10.9472, lng: -37.0731, radius: 5 },
                    { name: 'Rondônia', region: 'Norte', visits: 420, lat: -8.7619, lng: -63.9039, radius: 5 },
                    { name: 'Tocantins', region: 'Norte', visits: 350, lat: -10.2491, lng: -48.3243, radius: 5 },
                    { name: 'Acre', region: 'Norte', visits: 280, lat: -9.9754, lng: -67.8249, radius: 4 },
                    { name: 'Amapá', region: 'Norte', visits: 220, lat: 0.0344, lng: -51.0664, radius: 4 },
                    { name: 'Roraima', region: 'Norte', visits: 180, lat: 2.8197, lng: -60.6714, radius: 4 },
                    { name: 'Maranhão', region: 'Nordeste', visits: 630, lat: -2.5297, lng: -44.3025, radius: 6 }
                ];

                // Criamos o mapa utilizando uma biblioteca
                // Aqui estamos simulando a criação com HTML/CSS/JS
                const mapDiv = document.getElementById('mapChart');
                
                // Criamos um SVG para o mapa
                const svgNamespace = "http://www.w3.org/2000/svg";
                const svg = document.createElementNS(svgNamespace, "svg");
                svg.setAttribute("width", "100%");
                svg.setAttribute("height", "100%");
                svg.setAttribute("viewBox", "0 0 1000 800");
                svg.style.backgroundColor = "#f8fafc";
                
                // Simplificação do mapa do Brasil usando um contorno básico
                const brasilPath = document.createElementNS(svgNamespace, "path");
                brasilPath.setAttribute("d", "M245,276 C297,221 403,220 459,175 C514,131 543,69 600,69 C657,69 690,123 740,165 C791,207 834,314 834,373 C834,431 814,504 751,556 C688,609 582,645 520,673 C458,701 356,721 300,682 C244,643 196,594 172,535 C148,477 193,332 245,276 Z");
                brasilPath.setAttribute("fill", "#e2e8f0");
                brasilPath.setAttribute("stroke", "#cbd5e1");
                brasilPath.setAttribute("stroke-width", "2");
                svg.appendChild(brasilPath);
                
                // Adicionamos os círculos para cada estado
                regionData.forEach(state => {
                    // Convertemos as coordenadas lat/lng para coordenadas SVG
                    // Esta é uma simplificação e não reflete as coordenadas geográficas reais
                    const x = ((state.lng + 70) * 10) + 200;
                    const y = ((state.lat * -10) + 100) + 200;
                    
                    // Determinamos a cor baseada no número de visitas
                    let color;
                    if (state.visits > 4000) {
                        color = "#10b981"; // Verde para os maiores valores
                    } else if (state.visits > 2000) {
                        color = "#818cf8"; // Azul claro para valores médio-altos
                    } else if (state.visits > 1000) {
                        color = "#6366f1"; // Azul médio para valores médios
                    } else {
                        color = "#4f46e5"; // Azul escuro para valores baixos
                    }
                    
                    // Criamos a bolha
                    const circle = document.createElementNS(svgNamespace, "circle");
                    circle.setAttribute("cx", x);
                    circle.setAttribute("cy", y);
                    circle.setAttribute("r", state.radius);
                    circle.setAttribute("fill", color);
                    circle.setAttribute("opacity", "0.8");
                    circle.setAttribute("stroke", "#ffffff");
                    circle.setAttribute("stroke-width", "1");
                    circle.setAttribute("data-state", state.name);
                    circle.setAttribute("data-visits", state.visits);
                    circle.setAttribute("data-region", state.region);
                    
                    // Adicionamos a animação de pulsação
                    const animate = document.createElementNS(svgNamespace, "animate");
                    animate.setAttribute("attributeName", "r");
                    animate.setAttribute("values", `${state.radius};${state.radius + 2};${state.radius}`);
                    animate.setAttribute("dur", "3s");
                    animate.setAttribute("repeatCount", "indefinite");
                    circle.appendChild(animate);
                    
                    svg.appendChild(circle);
                    
                    // Criamos um título para exibir ao passar o mouse
                    const title = document.createElementNS(svgNamespace, "title");
                    title.textContent = `${state.name}: ${state.visits.toLocaleString()} visitas`;
                    circle.appendChild(title);
                });
                
                mapDiv.appendChild(svg);
                
                // Adicionamos interação nos círculos
                const circles = svg.querySelectorAll("circle");
                circles.forEach(circle => {
                    circle.addEventListener("mouseover", function() {
                        this.setAttribute("stroke-width", "2");
                        this.setAttribute("opacity", "1");
                    });
                    
                    circle.addEventListener("mouseout", function() {
                        this.setAttribute("stroke-width", "1");
                        this.setAttribute("opacity", "0.8");
                    });
                    
                    circle.addEventListener("click", function() {
                        const state = this.getAttribute("data-state");
                        const visits = this.getAttribute("data-visits");
                        const region = this.getAttribute("data-region");
                        
                        alert(`${state} (${region}): ${Number(visits).toLocaleString()} visitas`);
                    });
                });
            };
            
            // Inicializamos o mapa
            createBrazilMap();
        });
    </script>
