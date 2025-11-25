document.addEventListener('DOMContentLoaded', function() {
    // Sistema de menu lateral
    const sidebarButtons = document.querySelectorAll('.sidebar-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Controle de animações de fundo
    const toggleAnimationsBtn = document.getElementById('toggleAnimations');
    const aquariumBackground = document.querySelector('.aquarium-background');
    let animationsPaused = false;
    
    // Função para pausar/retomar animações
    function toggleAnimations() {
        console.log('Toggle animations called, current state:', animationsPaused);
        animationsPaused = !animationsPaused;
        
        // Selecionar apenas os elementos específicos de animação
        const fishElements = aquariumBackground.querySelectorAll('.fish');
        const bubbleElements = aquariumBackground.querySelectorAll('.bubble');
        
        console.log('Fish elements found:', fishElements.length);
        console.log('Bubble elements found:', bubbleElements.length);
        
        // Pausar/retomar peixes
        fishElements.forEach(fish => {
            if (animationsPaused) {
                fish.style.animationPlayState = 'paused';
            } else {
                fish.style.animationPlayState = 'running';
            }
        });
        
        // Pausar/retomar bolhas
        bubbleElements.forEach(bubble => {
            if (animationsPaused) {
                bubble.style.animationPlayState = 'paused';
            } else {
                bubble.style.animationPlayState = 'running';
            }
        });
        
        // Atualizar texto e ícone do botão
        if (toggleAnimationsBtn) {
            if (animationsPaused) {
                toggleAnimationsBtn.innerHTML = '<i class="fas fa-play"></i><span>Retomar Animações</span>';
                toggleAnimationsBtn.style.background = 'rgba(46, 204, 113, 0.2)';
                toggleAnimationsBtn.style.borderColor = 'rgba(46, 204, 113, 0.4)';
            } else {
                toggleAnimationsBtn.innerHTML = '<i class="fas fa-pause"></i><span>Pausar Animações</span>';
                toggleAnimationsBtn.style.background = 'rgba(52, 152, 219, 0.2)';
                toggleAnimationsBtn.style.borderColor = 'rgba(52, 152, 219, 0.4)';
            }
        }
        
        console.log('Animations toggled successfully. New state:', animationsPaused);
    }
    
    // Adicionar evento ao botão de controle de animações
    if (toggleAnimationsBtn) {
        console.log('Animation toggle button found, adding event listener');
        toggleAnimationsBtn.addEventListener('click', function(e) {
            console.log('Animation toggle button clicked');
            e.preventDefault(); // Prevenir comportamento padrão
            e.stopPropagation(); // Parar propagação do evento
            e.stopImmediatePropagation(); // Parar imediatamente qualquer outro handler
            
            // Verificar se o conteúdo ainda está visível antes de prosseguir
            const mainContent = document.querySelector('.main-content');
            const dashboardContent = document.getElementById('dashboard');
            
            if (mainContent && dashboardContent) {
                console.log('Content elements found, proceeding with toggle');
                toggleAnimations();
            } else {
                console.error('Content elements not found!');
            }
            
            return false; // Prevenir qualquer ação adicional
        });
    } else {
        console.log('Animation toggle button not found');
    }
    
    // Função para mudar de aba
    function switchTab(tabId) {
        // Remove active class from all buttons and contents
        sidebarButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));
        
        // Add active class to clicked button and corresponding content
        const targetButton = document.querySelector(`[data-tab="${tabId}"]`);
        if (targetButton) {
            targetButton.classList.add('active');
        }
        const targetContent = document.getElementById(tabId);
        if (targetContent) {
            targetContent.classList.add('active');
        }
    }
    
    sidebarButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            switchTab(tabId);
        });
    });
    
    // Verificar se há mensagem de sucesso no cadastro e redirecionar para usuários
    const successAlert = document.querySelector('#register .alert-success');
    if (successAlert) {
        // Aguardar um pouco para mostrar a mensagem e depois redirecionar
        setTimeout(() => {
            switchTab('users');
        }, 1500);
    }

    // Funcionalidade de editar e apagar usuários
    const editUserModal = document.getElementById('editUserModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const editUserForm = document.getElementById('editUserForm');
    const closeModal = document.querySelector('.close-modal');
    const cancelBtn = document.querySelector('.cancel-btn');

    // Abrir modal de edição
    document.querySelectorAll('.edit-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userIndex = this.getAttribute('data-user-index');
            const userCard = document.querySelector(`.user-card[data-user-index="${userIndex}"]`);
            
            if (userCard) {
                const name = userCard.querySelector('h4').textContent;
                const email = userCard.querySelector('.user-info p').textContent;
                const phoneElement = userCard.querySelector('.user-info p:nth-child(3)');
                const phone = phoneElement && phoneElement.querySelector('.fa-phone') ? 
                    phoneElement.textContent.replace(' ', '').trim() : '';
                
                document.getElementById('editUserIndex').value = userIndex;
                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPhone').value = phone;
                
                // Obter a role do usuário (se disponível)
                const userRole = userCard.querySelector('.user-role')?.textContent || 'user';
                document.getElementById('editRole').value = userRole;
                
                // Mostrar apenas o modal (sem overlay)
                editUserModal.style.display = 'flex';
            }
        });
    });

    // Fechar modal
    function closeEditModal() {
        editUserModal.style.display = 'none';
    }

    closeModal.addEventListener('click', closeEditModal);
    cancelBtn.addEventListener('click', closeEditModal);

    // Fechar modal ao clicar fora (no modal)
    editUserModal.addEventListener('click', function(e) {
        if (e.target === editUserModal) {
            closeEditModal();
        }
    });

    // Apagar usuário
    document.querySelectorAll('.delete-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userIndex = this.getAttribute('data-user-index');
            const userCard = document.querySelector(`.user-card[data-user-index="${userIndex}"]`);
            
            if (userCard) {
                const userName = userCard.querySelector('h4').textContent;
                
                if (confirm(`Tem certeza que deseja excluir o usuário "${userName}"?`)) {
                    // Criar formulário dinâmico para exclusão
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_user_index';
                    input.value = userIndex;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        });
    });
    
    // Configurar gráficos se existirem os elementos
    if (typeof sensorData !== 'undefined') {
        // Preparar dados para os últimos 12 registros
        const recentData = sensorData.slice(-12);
        const labels = recentData.map(item => {
            const date = new Date(item.timestamp);
            return date.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
        });
        
        const phData = recentData.map(item => item.ph);
        const tempData = recentData.map(item => item.temp);
        
        // Configuração do gráfico de pH
        const phCtx = document.getElementById('phChart');
        if (phCtx) {
            new Chart(phCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'pH',
                        data: phData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 6,
                            max: 8,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#fff'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#fff',
                                maxRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
        
        // Configuração do gráfico de temperatura
        const tempCtx = document.getElementById('tempChart');
        if (tempCtx) {
            new Chart(tempCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Temperatura (°C)',
                        data: tempData,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 20,
                            max: 30,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#fff'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#fff',
                                maxRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }
});
