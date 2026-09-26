<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('/images/budget-logo.png') }}" />
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.0/datatables.min.css"/>

    <link rel="stylesheet" href="{{ asset('css/template.css') }}?v=<?= filemtime('css/template.css'); ?>">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}?v=<?= filemtime('css/global.css'); ?>">
    <link rel="stylesheet" href="{{ asset('css/fab.css') }}?v=<?= filemtime('css/fab.css'); ?>">
    <link rel="stylesheet" href="{{ asset('css/switch-button.css') }}?v=<?= filemtime('css/switch-button.css'); ?>">
    <title>@yield('title') | Our Budget</title>
</head>
<body>
    <div class="wrapper">
        @component('components.header')@endcomponent

        <div id="content">
            <article class="content">
                @yield('content')
            </article>
        </div>

        @component('components.modals.loading')@endcomponent
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.0/datatables.min.js"></script>

    <script src="{{ asset('js/scripts.js') }}?v=<?= filemtime('js/scripts.js'); ?>"></script>
    <script src="{{ asset('js/jquery.mask.js') }}?v=<?= filemtime('js/jquery.mask.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/chart-config.js') }}?v=<?= filemtime('js/chart-config.js'); ?>"></script>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#sidebarCollapse').on('click', function () {
                jQuery('#sidebar').toggleClass('active');
            });

            jQuery('#dataTable').DataTable({
                "language": {
                    "lengthMenu": "Exibindo _MENU_ dados por página",
                    "zeroRecords": "Nenhum registro encontrado",
                    "info": "Exibindo página _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro disponível",
                    "infoFiltered": "(Filtrado de um total de _MAX_ registros)",
                    "paginate": {
                        "previous": "<<",
                        "next": ">>"
                    },
                    "search": "Pesquisar"
                },
            });

            jQuery('#dataTableSaidasPendentes').DataTable({
                "language": {
                    "lengthMenu": "Cobranças recusadas ou aguardando aprovação",
                    "zeroRecords": "Nenhum registro encontrado",
                    "info": "Exibindo página _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro disponível",
                    "infoFiltered": "(Filtrado de um total de _MAX_ registros)",
                    "paginate": {
                        "previous": "<<",
                        "next": ">>"
                    },
                    "search": "Pesquisar"
                },
            });

            jQuery('#dataTableSaidasAprovadas').DataTable({
                "language": {
                    "lengthMenu": "Cobranças aprovadas",
                    "zeroRecords": "Nenhum registro encontrado",
                    "info": "Exibindo página _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro disponível",
                    "infoFiltered": "(Filtrado de um total de _MAX_ registros)",
                    "paginate": {
                        "previous": "<<",
                        "next": ">>"
                    },
                    "search": "Pesquisar"
                },
            });
        });
    </script>
</body>
</html>
