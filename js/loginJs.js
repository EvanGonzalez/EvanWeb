$(document).ready(function(){
    // ============================================
    // TU CÓDIGO ORIGINAL DEL SLIDER
    // ============================================
    $('#goRight').on('click', function(){
        $('#slideBox').animate({
            'marginLeft' : '0'
        });
        $('.topLayer').animate({
            'marginLeft' : '100%'
        });
    });
    
    $('#goLeft').on('click', function(){
        $('#slideBox').animate({
            'marginLeft' : '50%'
        });
        $('.topLayer').animate({
            'marginLeft': '0'
        });
    });

    // ============================================
    // FUNCIONES PARA MENSAJES MEJORADOS
    // ============================================
    function showMessage(containerId, type, message) {
        const container = $('#' + containerId);
        const iconMap = {
            'error': 'fas fa-exclamation-circle',
            'success': 'fas fa-check-circle',
            'info': 'fas fa-info-circle'
        };
        
        container.html(`
            <div class="message message-${type}">
                <i class="${iconMap[type]}"></i>
                ${message}
            </div>
        `);
    }

    function showLoading(button) {
        const btnText = button.find('.btn-text');
        btnText.html('<span class="loading"></span>Procesando...');
        button.prop('disabled', true);
    }

    function hideLoading(button, originalText) {
        const btnText = button.find('.btn-text');
        btnText.text(originalText);
        button.prop('disabled', false);
    }
    // ============================================
    // EFECTOS SUAVES EN LOS INPUTS
    // ============================================
    $('input, select').on('focus', function() {
        $(this).parent().css('transform', 'scale(1.02)');
    });
    
    $('input, select').on('blur', function() {
        $(this).parent().css('transform', 'scale(1)');
    });

    // ============================================
    // MANEJO DE MENSAJES DEL SERVIDOR PHP
    // ============================================
    // Esta función la puedes llamar desde PHP si hay errores
    window.showServerMessage = function(type, message, formType) {
        if (formType === 'login') {
            showMessage('loginMessages', type, message);
        } else if (formType === 'register') {
            showMessage('registerMessages', type, message);
        }
    };

    // ============================================
    // FUNCIONES PÚBLICAS PARA USO EXTERNO
    // ============================================
    window.LoginSystem = {
        showSuccess: function(message, formType = 'login') {
            const containerId = formType === 'login' ? 'loginMessages' : 'registerMessages';
            showMessage(containerId, 'success', message);
        },
        
        showError: function(message, formType = 'login') {
            const containerId = formType === 'login' ? 'loginMessages' : 'registerMessages';
            showMessage(containerId, 'error', message);
        },
        
        showInfo: function(message, formType = 'login') {
            const containerId = formType === 'login' ? 'loginMessages' : 'registerMessages';
            showMessage(containerId, 'info', message);
        },
        
        switchToLogin: function() {
            $('#goLeft').click();
        },
        
        switchToRegister: function() {
            $('#goRight').click();
        }
    };

    // ============================================
    // NUEVO CÓDIGO PARA MANEJO DE PANELES
    // ============================================
    const goLeft = document.getElementById('goLeft');
    const goRight = document.getElementById('goRight');
    const leftPanel = document.querySelector('.left');
    const rightPanel = document.querySelector('.right');

    if (goLeft && goRight && leftPanel && rightPanel) {
        goLeft.addEventListener('click', function() {
            leftPanel.classList.remove('active');
            rightPanel.classList.add('active');
        });
        goRight.addEventListener('click', function() {
            rightPanel.classList.remove('active');
            leftPanel.classList.add('active');
        });
    }
});