@php
    $usuario = auth()->user();
@endphp
@extends('layouts.index')

@section('css')
    <style>
        :root {
            --whatsapp-green: #25D366;
            --dark-green: #128C7E;
            --light-green: #DCF8C6;
            --blue-gradient-start: #4F46E5;
            --blue-gradient-end: #7C3AED;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --white: #FFFFFF;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Main Layout */
        .payment-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 3rem;
            align-items: start;
        }

        /* Plans Section */
        .plans-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .plan-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .plan-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .plan-card.selected {
            background: var(--whatsapp-green);
            color: var(--white);
            border-color: var(--dark-green);
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .plan-card.selected::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 0;
            border-left: 30px solid transparent;
            border-top: 30px solid var(--dark-green);
        }

        .plan-card.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 8px;
            right: 8px;
            color: var(--white);
            font-size: 0.875rem;
        }

        .plan-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .plan-card.selected .plan-name {
            color: var(--white);
        }

        .plan-price {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            color: var(--gray-900);
        }

        .plan-card.selected .plan-price {
            color: var(--white);
        }

        .plan-period {
            font-size: 1rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .plan-card.selected .plan-period {
            color: rgba(255, 255, 255, 0.9);
        }

        .plan-features {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .plan-features li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            color: var(--gray-700);
        }

        .plan-card.selected .plan-features li {
            color: var(--white);
        }

        .plan-features li:last-child {
            margin-bottom: 0;
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            background: var(--whatsapp-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .plan-card.selected .feature-icon {
            background: var(--white);
        }

        .feature-icon i {
            font-size: 0.75rem;
            color: var(--white);
        }

        .plan-card.selected .feature-icon i {
            color: var(--whatsapp-green);
        }

        /* Payment Details */
        .payment-details {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
            position: sticky;
            top: 2rem;
        }

        .payment-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: var(--white);
            color: var(--gray-900);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .amount-display {
            background: var(--gray-50);
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .amount-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 0.25rem;
        }

        .amount-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .pay-button {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--blue-gradient-start) 0%, var(--blue-gradient-end) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 1.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .pay-button:active {
            transform: translateY(0);
        }

        .pay-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Security Badge */
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 0.75rem;
            background: var(--gray-50);
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .security-badge i {
            color: var(--whatsapp-green);
        }

        /* Popular Badge */
        .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--blue-gradient-start) 0%, var(--blue-gradient-end) 100%);
            color: var(--white);
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: var(--shadow-md);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .payment-layout {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .payment-details {
                position: static;
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .container-main {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .plans-section {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .plan-card {
                padding: 1.5rem;
            }

            .payment-details {
                padding: 1.5rem;
            }

            .plan-price {
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Smooth transitions */
        * {
            transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
        }

        /* Focus styles for accessibility */
        .plan-card:focus {
            outline: 2px solid var(--whatsapp-green);
            outline-offset: 2px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }
    </style>
@endsection

@section('content')
    <div class="container-main">
        <!-- Main Layout -->
        <div class="payment-layout">
            <!-- Plans Section -->
            <div class="plans-section">
                @foreach ($planes as $index => $itemPlan)
                    <div class="plan-card {{ $plan->id == $itemPlan->id ? 'selected' : '' }} selectPlan" data-plan="{{ $itemPlan->id }}" data-price="{{$itemPlan->valor}}">
                        @if ($plan->id == $itemPlan->id)
                            <div class="popular-badge">Más Popular</div>
                        @endif
                        <div class="plan-header">
                            <h3 class="plan-name">{{$itemPlan->nombre}}</h3>
                            <div class="plan-price">${{number_format($itemPlan->valor, 0, ',', '.')}}</div>
                            <div class="plan-period">/ Mes</div>
                        </div>
                        <ul class="plan-features">
                            <li>
                                <div class="feature-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                {{$itemPlan?->max_contactos}} Contactos Activos
                            </li>
                            @foreach ($itemPlan->serviciosHabilitados as $item)
                                <li>
                                    <div class="feature-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    {{$item?->nombre}}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <!-- Payment Details -->
            <div class="payment-details">
                <h2 class="payment-title">
                    <i class="fas fa-credit-card"></i>
                    Detalles de Pago
                </h2>

                <form id="paymentForm">
                    <div class="form-group">
                        <label class="form-label" for="currency">Divisa</label>
                        <select class="form-control form-select updateAmount" id="currency">
                            <option value="COP" data-symbol="$" data-rate="1">Peso Colombiano (COP)</option>
                            <option value="USD" data-symbol="$" data-rate="0.00025">Dólar Americano (USD)</option>
                            <option value="EUR" data-symbol="€" data-rate="0.00023">Euro (EUR)</option>
                            <option value="MXN" data-symbol="$" data-rate="0.0043">Peso Mexicano (MXN)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="period">Período de Pago</label>
                        <select class="form-control form-select updateAmount" id="period">
                            <option value="monthly" data-multiplier="1" data-discount="0">Mensual</option>
                            <option value="quarterly" data-multiplier="3" data-discount="0.05">Trimestral (5% desc.)</option>
                            <option value="yearly" data-multiplier="12" data-discount="0.15">Anual (15% desc.)</option>
                        </select>
                    </div>

                    <div class="amount-display">
                        <div class="amount-label">Monto a Pagar</div>
                        <div class="amount-value" id="amountDisplay">${{number_format($plan->valor, 0, ',', '.')}} COP</div>
                    </div>

                    <button type="button" class="pay-button processPayment">
                        <i class="fas fa-lock"></i>
                        Pagar Ahora
                    </button>

                    <div class="security-badge">
                        <i class="fas fa-shield-alt"></i>
                        Pago 100% seguro y encriptado
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://checkout.epayco.co/checkout.js"></script>
    <script src="{{mix('js/facturas/crear.js')}}"></script>
@endsection
