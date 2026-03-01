{{-- DataTables Scripts & Styles for DaisyUI --}}
@push('styles')
    <style>
        /* DataTables base reset — remove default styling */
        table.dataTable {
            border-collapse: collapse !important;
        }

        table.dataTable thead th {
            border-bottom: none !important;
        }

        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        /* Wrapper layout */
        .dataTables_wrapper {
            padding: 1rem;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            font-size: 0.875rem;
            color: oklch(var(--bc) / 0.6);
        }

        /* Top bar: length + filter */
        .dataTables_wrapper .dataTables_length {
            float: left;
            padding: 0 0 0.75rem;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
            padding: 0 0 0.75rem;
        }

        /* Select (entries per page) */
        .dataTables_wrapper .dataTables_length select {
            padding: 0.35rem 2rem 0.35rem 0.75rem;
            border: 1px solid oklch(var(--bc) / 0.15);
            border-radius: var(--radius-field, 0.5rem);
            font-size: 0.875rem;
            background-color: oklch(var(--b1));
            color: oklch(var(--bc));
            outline: none;
            appearance: auto;
            margin: 0 0.35rem;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: oklch(var(--p));
            box-shadow: 0 0 0 2px oklch(var(--p) / 0.15);
        }

        /* Search input */
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.4rem 0.75rem;
            border: 1px solid oklch(var(--bc) / 0.15);
            border-radius: var(--radius-field, 0.5rem);
            font-size: 0.875rem;
            background-color: oklch(var(--b1));
            color: oklch(var(--bc));
            outline: none;
            margin-left: 0.35rem;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: oklch(var(--p));
            box-shadow: 0 0 0 2px oklch(var(--p) / 0.15);
        }

        /* Bottom bar: info + pagination */
        .dataTables_wrapper .dataTables_info {
            float: left;
            padding: 0.75rem 0 0;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            padding: 0.75rem 0 0;
        }

        /* Pagination buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-block;
            padding: 0.3rem 0.65rem;
            margin: 0 0.1rem;
            border-radius: var(--radius-field, 0.375rem);
            font-size: 0.8rem;
            cursor: pointer;
            border: none !important;
            background: transparent !important;
            color: oklch(var(--bc) / 0.6) !important;
            transition: background 0.15s, color 0.15s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: oklch(var(--p)) !important;
            color: oklch(var(--pc)) !important;
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
            background: oklch(var(--bc) / 0.08) !important;
            color: oklch(var(--bc)) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.3;
            cursor: default;
        }

        /* Sorting arrows */
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            background-image: none !important;
            position: relative;
            cursor: pointer;
        }

        table.dataTable thead .sorting::after {
            content: '⇅';
            position: absolute;
            right: 0.5rem;
            opacity: 0.25;
            font-size: 0.75rem;
        }

        table.dataTable thead .sorting_asc::after {
            content: '↑';
            position: absolute;
            right: 0.5rem;
            opacity: 0.6;
            font-size: 0.75rem;
        }

        table.dataTable thead .sorting_desc::after {
            content: '↓';
            position: absolute;
            right: 0.5rem;
            opacity: 0.6;
            font-size: 0.75rem;
        }

        /* Empty table */
        .dataTables_empty {
            padding: 2rem !important;
            text-align: center;
            color: oklch(var(--bc) / 0.4);
        }

        /* Responsive clearfix */
        .dataTables_wrapper::after {
            content: '';
            display: table;
            clear: both;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
@endpush
