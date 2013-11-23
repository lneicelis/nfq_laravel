@if(Session::has('gritter'))
    <script>
        $(function(){
            $(document).ready(function(){
            @foreach(Session::get('gritter') as $msg)
                $.grit("{{ $msg['type'] }}", "{{ $msg['title'] }}", "{{ $msg['message'] }}");
            @endforeach
            });
        });
    </script>
@endif