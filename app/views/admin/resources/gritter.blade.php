<link rel="stylesheet" href="{{ URL::asset('assets/css/jquery.gritter.css') }}" />
<script src="{{ URL::asset('assets/js/jquery.gritter.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom.js') }}"></script>

@if(Session::has('gritter'))
    <script>
        $(function(){
            $(document).ready(function(){
            @foreach(Session::get('gritter') as $msg)
                gritter('{{ $msg['type'] }}', '{{ $msg['title'] }}', '{{ $msg['message'] }}');
            @endforeach
            });
        });
    </script>
@endif