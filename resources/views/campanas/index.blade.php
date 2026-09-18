@extends('layouts.index')

@section('css')
    <style>
        :root {
            --primary-color: #1E6A75;
            --primary-light: #147888;
            --primary-dark: #0d4b55;
            --secondary-color: #0d4b55;
            --secondary-light: #1E6A75;
            --accent-color: #6366F1;
            --success-color: #1E6A75;
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
        }

        .main-container {
            min-height: 100vh;
            padding: 2rem 0;
        }

        /* Stepper Styles */
        .stepper {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stepper-item {
            display: flex;
            align-items: center;
            position: relative;
            flex: 1;
            max-width: 200px;
        }

        .stepper-item:not(:last-child)::after {
            /* content: ''; */
            position: absolute;
            top: 50%;
            right: -50%;
            width: 100%;
            height: 2px;
            background: var(--gray-200);
            transform: translateY(-50%);
            z-index: 1;
        }

        .stepper-item.active:not(:last-child)::after {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .stepper-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gray-200);
            color: var(--gray-500);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.75rem;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .stepper-item.active .stepper-number {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .stepper-item.completed .stepper-number {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .stepper-text {
            font-weight: 500;
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .stepper-item.active .stepper-text {
            color: linear-gradient(135deg, #28a745, #20c997);
            font-weight: 600;
        }

        /* Card Styles */
        .campaign-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .campaign-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.25rem 1.5rem;
            border: none;
        }

        .card-header-custom h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Styles */
        .form-control,
        .form-select {
            border: 2px solid var(--gray-200);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-1px);
        }

        .btn-secondary-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: linear-gradient(135deg, #1E6A75, var(--secondary-color));
            transform: translateY(-1px);
            color: white;
        }

        /* Table Styles */
        .contacts-table {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: var(--gray-50);
            border: none;
            font-weight: 600;
            color: var(--gray-700);
            padding: 1rem;
        }

        .table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        /* Step Content */
        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        /* Variable Tags */
        .variable-tag {
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .variable-tag:hover {
            background: #4F46E5;
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stepper {
                flex-direction: column;
                gap: 1rem;
            }

            .stepper-item::after {
                display: none;
            }
        }
    </style>

    <style>
        .modal-header-wizard {
            background: linear-gradient(135deg, var(--whatsapp-green) 0%, var(--whatsapp-dark) 100%);
            padding: 30px 20px;
            border-radius: 12px 12px 0 0;
            color: white;
        }

        .modal-header-wizard h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-body-wizard {
            padding: 40px 30px;
            background: white;
        }

        .breadcrumb-wizard {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
        }

        .breadcrumb-wizard::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gray-300);
            z-index: 0;
        }

        .breadcrumb-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            z-index: 1;
            position: relative;
        }

        .breadcrumb-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
            background: var(--gray-200);
            color: var(--gray-600);
            border: 2px solid var(--gray-300);
            transition: all 0.3s ease;
        }

        .breadcrumb-step.active .breadcrumb-step-circle {
            background: var(--whatsapp-green);
            color: white;
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 4px rgba(37, 211, 102, 0.15);
        }

        .breadcrumb-step.completed .breadcrumb-step-circle {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .breadcrumb-step.completed .breadcrumb-step-circle::after {
            content: '✓';
            position: absolute;
        }

        .breadcrumb-step-label {
            font-size: 12px;
            color: var(--gray-600);
            font-weight: 500;
            text-align: center;
            max-width: 80px;
        }

        .breadcrumb-step.active .breadcrumb-step-label {
            color: var(--whatsapp-green);
            font-weight: 600;
        }

        .wizard-step {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .wizard-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .step-description {
            color: var(--gray-600);
            margin-bottom: 24px;
            font-size: 0.95rem;
        }

        .template-card {
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .template-card:hover {
            border-color: var(--whatsapp-green);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.15);
            transform: translateY(-2px);
        }

        .template-card.selected {
            border-color: var(--whatsapp-green);
            background: var(--whatsapp-light);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.2);
        }

        .template-card.selected::after {
            content: '✓';
            position: absolute;
            top: 15px;
            right: 15px;
            width: 28px;
            height: 28px;
            background: var(--whatsapp-green);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }

        .template-name {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .template-type {
            display: inline-block;
            background: var(--whatsapp-green);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .template-preview {
            background: var(--gray-50);
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--gray-700);
            border-left: 3px solid var(--whatsapp-green);
        }

        .users-table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
        }

        .users-table {
            margin: 0;
        }

        .users-table thead {
            background: var(--gray-50);
            position: sticky;
            top: 0;
        }

        .users-table th {
            border: none;
            color: var(--gray-700);
            font-weight: 600;
            padding: 12px 16px;
            font-size: 0.9rem;
            background: var(--gray-100);
        }

        .users-table td {
            padding: 12px 16px;
            border: none;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-800);
        }

        .users-table tr:hover {
            background: var(--whatsapp-light);
        }

        .users-table tbody tr:last-child td {
            border-bottom: none;
        }

        .user-checkbox {
            cursor: pointer;
            margin-left: 1rem;
        }

        .user-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--whatsapp-green);
        }

        .user-name {
            font-weight: 500;
            color: var(--gray-900);
        }

        .user-phone {
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .user-stats {
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        .select-all-container {
            padding: 12px 16px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .select-all-container input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--whatsapp-green);
        }

        .select-all-container label {
            margin: 0;
            cursor: pointer;
            font-weight: 600;
            color: var(--gray-900);
        }

        .results-table-container {
            max-height: 450px;
            overflow-y: auto;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
        }

        .results-table {
            margin: 0;
        }

        .results-table thead {
            background: var(--gray-50);
            position: sticky;
            top: 0;
        }

        .results-table th {
            border: none;
            color: var(--gray-700);
            font-weight: 600;
            padding: 12px 16px;
            font-size: 0.9rem;
            background: var(--gray-100);
        }

        .results-table td {
            padding: 12px 16px;
            border: none;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-800);
        }

        .results-table tbody tr:last-child td {
            border-bottom: none;
        }

        .probability-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .probability-high {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
        }

        .probability-medium {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning-color);
        }

        .probability-low {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger-color);
        }

        .probability-bar {
            width: 100%;
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 4px;
        }

        .probability-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--whatsapp-green), var(--whatsapp-dark));
            transition: width 0.3s ease;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-will-open {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
        }

        .status-wont-open {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger-color);
        }

        .modal-footer-wizard {
            padding: 20px 30px;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .btn-custom {
            padding: 10px 28px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-primary-wizard {
            background: linear-gradient(135deg, var(--whatsapp-green) 0%, var(--whatsapp-dark) 100%);
            color: white;
        }

        .btn-primary-wizard:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
        }

        .btn-primary-wizard:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-secondary-wizard {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .btn-secondary-wizard:hover:not(:disabled) {
            background: var(--gray-300);
        }

        .btn-secondary-wizard:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .results-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            padding: 16px;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        .stats-label {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .stats-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--whatsapp-green);
        }

        .selected-info {
            background: var(--whatsapp-light);
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid var(--whatsapp-green);
            margin-bottom: 20px;
            color: var(--gray-700);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .modal-body-wizard {
                padding: 20px;
            }

            .modal-footer-wizard {
                padding: 16px;
                flex-direction: column;
            }

            .breadcrumb-step-label {
                font-size: 10px;
                max-width: 60px;
            }

            .breadcrumb-step-circle {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }

            .users-table-container,
            .results-table-container {
                max-height: 300px;
            }

            .btn-custom {
                width: 100%;
                padding: 12px;
            }
        }
    </style>
    <style>
        :root {
            --primary: #1e6f78;
            --accent: #2c8f99;
            --wa: #25d366;
            --wa-deep: #128c7e;
            --bg: #f7f9fa;
            --card: #ffffff;
            --ink: #0f172a;
            --muted: #64748b;
            --soft: #94a3b8;
            --border: #e9edf2;
            --radius: 18px;
            --shadow-xs: 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-sm:
                0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03);
            --shadow-md:
                0 12px 28px -18px rgba(15, 23, 42, 0.3),
                0 2px 8px -4px rgba(15, 23, 42, 0.06);
        }

        .muted {
            color: var(--muted);
        }

        .page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 28px 22px 64px;
        }

        /* Topbar */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: grid;
            place-items: center;
            color: #fff;
            box-shadow: var(--shadow-sm);
        }

        .btn-wa {
            background: var(--wa-deep);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 16px;
        }

        .btn-wa:hover {
            background: #0f7a6d;
            color: #fff;
        }

        .btn-ghost {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-weight: 500;
            color: #334155;
            padding: 9px 14px;
        }

        .btn-ghost:hover {
            border-color: #cfd8e3;
            background: #fbfdfe;
            color: var(--ink);
        }

        /* Toolbar */
        .toolbar {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 14px;
            box-shadow: var(--shadow-xs);
            margin-bottom: 22px;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 220px;
        }

        .search-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--soft);
            font-size: 13px;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 13.5px;
            padding: 9px 12px;
            color: var(--ink);
            background-color: #fcfdfe;
        }

        .search-wrap .form-control {
            padding-left: 38px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(44, 143, 153, 0.12);
            background: #fff;
        }

        .viewtoggle {
            display: inline-flex;
            background: #f1f5f7;
            border-radius: 12px;
            padding: 3px;
            border: 1px solid var(--border);
        }

        .viewtoggle button {
            border: none;
            background: transparent;
            border-radius: 9px;
            padding: 7px 13px;
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .viewtoggle button.active {
            background: #fff;
            color: var(--ink);
            box-shadow: var(--shadow-xs);
        }

        .chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .chip {
            border: 1px solid var(--border);
            background: #fff;
            border-radius: 999px;
            padding: 5px 13px;
            font-size: 12.5px;
            color: var(--muted);
            cursor: pointer;
            transition: 0.18s;
        }

        .chip:hover {
            border-color: #cfd8e3;
            color: var(--ink);
        }

        .chip.active {
            background: rgba(37, 211, 102, 0.1);
            border-color: rgba(18, 140, 126, 0.35);
            color: var(--wa-deep);
            font-weight: 600;
        }

        /* Card */
        .grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 640px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1080px) {
            .grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1500px) {
            .grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .c-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-xs);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition:
                transform 0.22s cubic-bezier(0.2, 0.8, 0.2, 1),
                box-shadow 0.22s,
                border-color 0.22s;
            opacity: 0;
            transform: translateY(12px);
        }

        .c-card.in {
            opacity: 1;
            transform: none;
        }

        .c-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: #dfe6ee;
        }

        .c-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 14px 10px;
        }

        .c-title {
            font-weight: 600;
            font-size: 14.5px;
            line-height: 1.3;
            margin: 0 14px 2px;
            letter-spacing: -0.01em;
        }

        .c-sub {
            margin: 0 14px 12px;
            font-size: 12px;
            color: var(--soft);
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 999px;
            padding: 4px 10px;
            border: 1px solid transparent;
            letter-spacing: 0.01em;
        }

        .badge-status .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .st-sent {
            background: rgba(37, 211, 102, 0.1);
            color: #0f7a5f;
            border-color: rgba(37, 211, 102, 0.22);
        }

        .st-scheduled {
            background: rgba(59, 130, 246, 0.09);
            color: #2563eb;
            border-color: rgba(59, 130, 246, 0.2);
        }

        .st-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #b45309;
            border-color: rgba(245, 158, 11, 0.22);
        }

        .st-processing {
            background: rgba(139, 92, 246, 0.1);
            color: #6d28d9;
            border-color: rgba(139, 92, 246, 0.2);
        }

        .st-error {
            background: rgba(239, 68, 68, 0.09);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .st-cancelled {
            background: rgba(100, 116, 139, 0.1);
            color: #475569;
            border-color: rgba(100, 116, 139, 0.2);
        }

        .st-draft {
            background: #f1f5f9;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .st-processing .dot {
            animation: pulse 1.4s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.25;
            }
        }

        .type-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 500;
            color: var(--muted);
            background: #f5f7f9;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 4px 9px;
        }

        .kebab {
            margin-left: auto;
            width: 30px;
            height: 30px;
            border-radius: 9px;
            border: 1px solid transparent;
            background: transparent;
            color: var(--soft);
            display: grid;
            place-items: center;
        }

        .kebab:hover {
            background: #f3f6f8;
            color: var(--ink);
        }

        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            padding: 6px;
            font-size: 13.5px;
            min-width: 200px;
        }

        .dropdown-item {
            border-radius: 9px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #334155;
        }

        .dropdown-item i {
            width: 15px;
            color: var(--soft);
        }

        .dropdown-item:hover {
            background: #f5f8fa;
        }

        .dropdown-item.danger,
        .dropdown-item.danger i {
            color: #dc2626;
        }

        .dropdown-item.danger:hover {
            background: rgba(239, 68, 68, 0.07);
        }

        /* WhatsApp preview */
        .wa-preview {
            margin: 0 14px;
            background: #efeae2;
            background-image: radial-gradient(rgba(0, 0, 0, 0.035) 1px,
                    transparent 1px);
            background-size: 14px 14px;
            border: 1px solid #e3ddd3;
            border-radius: 14px;
            padding: 11px;
        }

        .bubble {
            background: #fff;
            border-radius: 12px;
            border-top-left-radius: 4px;
            box-shadow: 0 1px 1px rgba(11, 20, 26, 0.1);
            overflow: hidden;
            max-width: 100%;
        }

        .bubble .media {
            position: relative;
            display: block;
            width: 100%;
            aspect-ratio: 16/9;
            overflow: hidden;
            background: #ddd;
        }

        .bubble .media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .bubble .media .veil {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg,
                    rgba(0, 0, 0, 0.1),
                    rgba(0, 0, 0, 0.28));
        }

        .play {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.92);
            display: grid;
            place-items: center;
            color: #0f172a;
            font-size: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
            transition: 0.2s;
        }

        .c-card:hover .play {
            transform: scale(1.07);
        }

        .dur {
            position: absolute;
            right: 8px;
            bottom: 8px;
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 6px;
        }

        .media-tag {
            position: absolute;
            left: 8px;
            top: 8px;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 6px;
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .bubble .txt {
            padding: 9px 11px 8px;
            font-size: 13px;
            line-height: 1.5;
            color: #111b21;
            /* white-space: pre-wrap; */
            word-break: break-word;
        }

        .clamp {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .clamp.open {
            -webkit-line-clamp: unset;
        }

        .more-link {
            display: inline-block;
            margin-top: 4px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--wa-deep);
            cursor: pointer;
        }

        .more-link:hover {
            text-decoration: underline;
        }

        .bubble .meta {
            display: flex;
            justify-content: flex-end;
            gap: 4px;
            align-items: center;
            padding: 0 11px 7px;
            font-size: 10.5px;
            color: #8696a0;
        }

        .bubble .meta .fa-check-double {
            color: #53bdeb;
        }

        .doc-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px;
            background: #f5f6f6;
            border-radius: 10px;
            margin: 9px 9px 0;
        }

        .doc-row .ic {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #fff;
            display: grid;
            place-items: center;
            color: #dc2626;
        }

        .wa-btns {
            border-top: 1px solid rgba(0, 0, 0, 0.07);
            display: flex;
            flex-direction: column;
        }

        .wa-btn {
            padding: 8px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            color: #00a5f4;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            cursor: pointer;
            background: transparent;
        }

        .wa-btn:first-child {
            border-top: none;
        }

        .wa-btn:hover {
            background: rgba(0, 165, 244, 0.06);
        }

        .btns-more {
            font-size: 11.5px;
            color: var(--muted);
            padding: 6px 8px;
            text-align: center;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.02);
        }

        /* metrics + footer */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 14px 14px 0;
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            padding: 12px 14px 2px;
        }

        .metric {
            text-align: center;
        }

        .metric .v {
            font-family: "Space Grotesk", sans-serif;
            font-weight: 600;
            font-size: 14px;
        }

        .metric .l {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--soft);
            margin-top: 1px;
        }

        .metric.fail .v {
            color: #dc2626;
        }

        .bar {
            height: 4px;
            border-radius: 999px;
            background: #eef2f5;
            margin: 10px 14px 0;
            overflow: hidden;
            display: flex;
        }

        .bar span {
            display: block;
            height: 100%;
        }

        .bar .b1 {
            background: var(--wa);
        }

        .bar .b2 {
            background: #7dd3a5;
        }

        .dates {
            margin-top: auto;
            padding: 12px 14px 14px;
            display: flex;
            gap: 10px;
            justify-content: space-between;
            align-items: flex-end;
        }

        .date-item .l {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--soft);
        }

        .date-item .v {
            font-size: 12.5px;
            font-weight: 500;
            color: #334155;
            margin-top: 2px;
        }

        .stats-link {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--wa-deep);
            text-decoration: none;
            white-space: nowrap;
        }

        .stats-link:hover {
            text-decoration: underline;
        }

        .err-note {
            margin: 12px 14px 0;
            background: rgba(239, 68, 68, 0.06);
            border: 1px solid rgba(239, 68, 68, 0.18);
            color: #b91c1c;
            border-radius: 12px;
            padding: 9px 11px;
            font-size: 12.5px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        /* skeleton */
        .sk {
            background: linear-gradient(90deg,
                    #eef2f5 25%,
                    #f7fafc 37%,
                    #eef2f5 63%);
            background-size: 400% 100%;
            animation: shimmer 1.3s infinite;
            border-radius: 8px;
        }

        @keyframes shimmer {
            0% {
                background-position: 100% 0;
            }

            100% {
                background-position: 0 0;
            }
        }

        /* states */
        .state-box {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 56px 24px;
            text-align: center;
            box-shadow: var(--shadow-xs);
        }

        .state-ic {
            width: 72px;
            height: 72px;
            border-radius: 22px;
            background: rgba(37, 211, 102, 0.1);
            color: var(--wa-deep);
            display: grid;
            place-items: center;
            font-size: 28px;
            margin: 0 auto 18px;
        }

        .state-ic.err {
            background: rgba(239, 68, 68, 0.09);
            color: #dc2626;
        }

        .pagination-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 26px;
        }

        .pg {
            display: flex;
            gap: 6px;
        }

        .pg button {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: #334155;
            font-size: 13px;
            font-weight: 500;
        }

        .pg button.active {
            background: var(--wa-deep);
            border-color: var(--wa-deep);
            color: #fff;
        }

        .pg button:disabled {
            opacity: 0.45;
        }

        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 30px 70px -30px rgba(15, 23, 42, 0.45);
        }

        .offcanvas {
            border-left: 1px solid var(--border);
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="bi bi-megaphone-fill fs-2 text-white"></i>
                    {{ __('Mis Campañas') }}
                </h1>
                <p class="subtitle mb-0">{{ __('Configura y lanza tu campaña de WhatsApp Business') }}</p>
            </div>
            <div class="mt-3 mt-md-0">
                @can('campana.crear')
                    @if (servicioPlan('analisis.ia'))
                        <button type="button" class="btn btn-new-template" id="abrirModalPredictivo">
                            <i class="fas fa-rocket"></i>
                            {{ __('Análisis Predictivo') }}
                        </button>
                    @endif
                    <button type="button" class="btn btn-new-template" data-bs-toggle="modal"
                        data-bs-target="#modalCrearCampana">
                        <i class="fas fa-plus fs-1"></i>
                        {{ __('Crear Campaña') }}
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="toolbar">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="form-control" id="q" placeholder="{{ __('Buscar campañas...') }}" />
            </div>
            <div style="max-width: 170px !important;">
                <select class="form-select" id="fType" data-control="select2"
                    data-placeholder="{{ __('Tipo de plantilla') }}" data-allow-clear="true" data-hide-search="true">
                    <option value=""></option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->codigo }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ms-auto viewtoggle">
                <button type="button" class="active" id="btnTabListadoTarjetas">
                    <i class="fa-solid fa-grip"></i>
                    {{ __('Tarjetas') }}
                </button>
                <button type="button" id="toTable">
                    <i class="fa-solid fa-list"></i>
                    {{ __('Tabla') }}
                </button>
            </div>
        </div>
        <div class="chips" id="quickChips">
            <span class="chip active" data-quick="10">Todas</span>
            @foreach ($estados as $estado)
                <span class="chip" data-quick="{{ $estado->codigo }}">{{ $estado->nombre }}</span>
            @endforeach
        </div>
    </div>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="contenedor" id="kt_content_container">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0">
                    <div class="card card-flush">
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade" id="tabListadoCampanasTabla"
                                            role="tabpanel">
                                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll"
                                                data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                                data-kt-scroll-max-height="auto"
                                                data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header"
                                                data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body"
                                                data-kt-scroll-offset="5px" style="max-height: 410px;">
                                                <div class="table-responsive">
                                                    <table border="1" class="table table-striped table-bordered"
                                                        id="tablaCampanas">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%" class="text-center all">#</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Nombre') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Descripción') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Enviado por') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Estado') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Fecha envio') }}</th>
                                                                <th width="10%" class="text-center none">
                                                                    {{ __('Fecha creación') }}</th>
                                                                <th width="10%" class="text-center none">
                                                                    {{ __('Plantilla') }}</th>
                                                                <th width="10%" class="text-center none">
                                                                    {{ __('Tipo') }}</th>
                                                                <th width="10%" class="text-center all">
                                                                    {{ __('Acciones') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show active" id="tabListadoCampanasTarjeta" role="tabpanel">
                                            <div class="seccionListadoCampanas"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @can('campana.crear')
        @component('campanas.modals.crear')
            @slot('etiquetas', $etiquetas)
            @slot('plantillas', $plantillas)
            @slot('numeroTel', $numeroTel)
            @slot('categorias', $categorias)
        @endcomponent

        @component('campanas.modals.prediccion')
        @endcomponent
    @endcan

    @component('campanas.modals.detalle')
    @endcomponent

    @can('campana.editar')
        @component('campanas.modals.modal')
        @endcomponent
    @endcan

    @component('campanas.modals.modal-respuesta')
    @endcomponent
    @component('sistema.modales.modal-errores')
    @endcomponent
    @component('campanas.modals.modal-links')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/campanas/principal.js') }}"></script>
    <script src="{{ mix('/js/campanas/prediccion.js') }}"></script>
@endsection
