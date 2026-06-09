"use strict";

$(function () {
    //
});

const iniciarComponentes = (form = "") => {
}

$(document).on('click', '.selectPlan', function() {
    selectPlan(this);
});

// Select plan function
function selectPlan(element) {
    // Remove selected class from all plans
    document.querySelectorAll('.plan-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked plan
    element.classList.add('selected');

    // Update amount display
    updateAmount();

    // Add smooth animation
    element.style.transform = 'scale(1.02)';
    setTimeout(() => {
        element.style.transform = '';
    }, 200);
}

// Update amount based on currency and period
function updateAmount() {
    const currencySelect = document.getElementById('currency');
    const periodSelect = document.getElementById('period');
    const amountDisplay = document.getElementById('amountDisplay');

    const selectedCurrency = currencySelect.options[currencySelect.selectedIndex];
    const selectedPeriod = periodSelect.options[periodSelect.selectedIndex];

    const currencySymbol = selectedCurrency.dataset.symbol;
    const currencyRate = parseFloat(selectedCurrency.dataset.rate);
    const currencyCode = selectedCurrency.value;

    const periodMultiplier = parseInt(selectedPeriod.dataset.multiplier);
    const discount = parseFloat(selectedPeriod.dataset.discount);

    const selectedPlanEl = document.querySelector('.selectPlan.selected');
    if (!selectedPlanEl) throw new Error("Debes seleccionar un plan.");

    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        if (response.estado == 'success') {
            // Calculate base amount
            let amount = parseInt(response.plan.valor) * periodMultiplier;

            // Apply discount
            if (discount > 0) {
                amount = amount * (1 - discount);
            }

            // Convert currency
            if (currencyCode !== 'COP') {
                amount = amount * currencyRate;
            }

            // Format amount
            let formattedAmount;
            if (currencyCode === 'COP') {
                formattedAmount = new Intl.NumberFormat('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                }).format(amount);
            } else {
                formattedAmount = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: currencyCode,
                    minimumFractionDigits: 2
                }).format(amount);
            }

            amountDisplay.textContent = formattedAmount;

            // Add animation to amount change
            amountDisplay.style.transform = 'scale(1.1)';
            amountDisplay.style.color = 'var(--whatsapp-green)';
            setTimeout(() => {
                amountDisplay.style.transform = '';
                amountDisplay.style.color = '';
            }, 300);
        }
    }

    const error = (response) => {
    }

    generalidades.get(route('planes.show', {plan: selectedPlanEl.dataset.plan}), config, success, error);
}

// Process payment function
async function processPayment() {
    const payButton = document.querySelector('.pay-button');
    const originalContent = payButton.innerHTML;
    payButton.disabled = true;
    payButton.innerHTML = '<div class="loading"></div> Procesando...';

    try {
        // 1️⃣ Obtener los datos del formulario
        const selectedPlanEl = document.querySelector('.selectPlan.selected');
        if (!selectedPlanEl) throw new Error("Debes seleccionar un plan.");

        const planId = selectedPlanEl.dataset.plan;
        const currency = document.getElementById('currency').value;
        const currencySelect = document.getElementById('currency');
        const period = document.getElementById('period').value;
        const tiempo = document.getElementById('period').selectedOptions[0].textContent;
        const selectedCurrency = currencySelect.options[currencySelect.selectedIndex];

        // Prevenir manipulación de monto en el frontend
        // Solo mostrar, pero no usar ese valor para pagar
        const amountDisplay = document.getElementById('amountDisplay').textContent;

        // 2️⃣ Validar con el servidor que el plan y monto sean correctos
        const config = {
            method: 'POST',
            headers: {
                'Accept': generalidades.CONTENT_TYPE_JSON,
                'Content-Type': generalidades.CONTENT_TYPE_JSON,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                plan_id: planId,
                currency: currency,
                period: period,
                currency_rate: parseFloat(selectedCurrency.dataset.rate)
            })
        };

        const response = await fetch(route('validar.invoice.activo'), config);
        const result = await response.json();

        if (!response.ok || result.estado !== 'success') {
            throw new Error(result.mensaje || 'No se pudo validar el plan o la factura.');
        }

        // ✅ 3️⃣ Usar SOLO la información que viene del servidor
        const validatedData = result.data; // el backend debe devolver los datos validados (nombre, precio, descripción, invoice, etc.)

        var handler = ePayco.checkout.configure({
            key: validatedData.public_key, // Public Key de ePayco
            test: validatedData.epayco_test ?? true // false en producción
        });

        var data = {
            name: validatedData.name, // del backend
            description: validatedData.description,
            invoice: validatedData.invoice, // generado en el backend
            currency: validatedData.currency,
            amount: validatedData.amount, // del backend, no del front
            tax_base: validatedData.amount,
            tax: "0",
            country: "CO",
            lang: "es",
            external: "false",
            confirmation: route('epayco.confirmation'),
            response: route('home'),
            extra1: validatedData.cod_plan,
            extra2: validatedData.cod_usuario,
            extra3: validatedData.tiempo
        };

        // 4️⃣ Abrir ePayco solo con datos verificados
        handler.open(data);

    } catch (error) {
        console.error('Error en el proceso de pago:', error);
        generalidades.toastrGenerico('error', error.message);
    } finally {
        payButton.disabled = false;
        payButton.innerHTML = originalContent;
    }
}

// Show payment success
function showPaymentSuccess() {
    // Create success modal
    const modal = document.createElement('div');
    modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                animation: fadeIn 0.3s ease;
            `;

    modal.innerHTML = `
                <div style="
                    background: white;
                    padding: 3rem;
                    border-radius: 16px;
                    text-align: center;
                    max-width: 400px;
                    margin: 1rem;
                    box-shadow: var(--shadow-xl);
                    animation: slideUp 0.3s ease;
                ">
                    <div style="
                        width: 80px;
                        height: 80px;
                        background: var(--whatsapp-green);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 1.5rem;
                    ">
                        <i class="fas fa-check" style="color: white; font-size: 2rem;"></i>
                    </div>
                    <h3 style="color: var(--gray-900); margin-bottom: 1rem; font-size: 1.5rem;">¡Pago Exitoso!</h3>
                    <p style="color: var(--gray-600); margin-bottom: 2rem;">
                        Tu suscripción al ${plans[selectedPlan.name].name} ha sido activada correctamente.
                    </p>
                    <button onclick="this.parentElement.parentElement.remove()" style="
                        background: var(--whatsapp-green);
                        color: white;
                        border: none;
                        padding: 0.75rem 2rem;
                        border-radius: 8px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.background='var(--dark-green)'" onmouseout="this.style.background='var(--whatsapp-green)'">
                        Continuar
                    </button>
                </div>
            `;

    document.body.appendChild(modal);

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes slideUp {
                    from { transform: translateY(30px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
            `;
    document.head.appendChild(style);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    // updateAmount();

    // Add keyboard navigation for plans
    document.querySelectorAll('.plan-card').forEach((card, index) => {
        card.setAttribute('tabindex', '0');
        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                selectPlan(this);
            }
        });
    });

    // Add form validation
    const form = document.getElementById('paymentForm');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        processPayment();
    });
});

// Add smooth scrolling for mobile
if (window.innerWidth <= 768) {
    document.querySelectorAll('.plan-card').forEach(card => {
        card.addEventListener('click', function () {
            setTimeout(() => {
                document.querySelector('.payment-details').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 300);
        });
    });
}

$(document).on('change', '.updateAmount', function() {
    updateAmount();
});

$(document).on('click', '.processPayment', function() {
    processPayment();
});
