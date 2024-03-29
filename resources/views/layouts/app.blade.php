<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('/images/budget-logo.png') }}" />
    <meta name="_token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
