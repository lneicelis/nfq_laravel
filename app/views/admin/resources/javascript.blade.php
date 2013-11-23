
<!-- ace settings handler -->

<script src="{{ URL::asset('assets/js/ace-extra.min.js') }}"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!--[if lt IE 9]>
<script src="{{ URL::asset('assets/js/html5shiv.js') }}"></script>
<script src="{{ URL::asset('assets/js/respond.min.js') }}"></script>
<![endif]-->

<script>
    var token = "{{ csrf_token() }}";
    var $path_base = "{{ URL::asset('/') }}";
</script>