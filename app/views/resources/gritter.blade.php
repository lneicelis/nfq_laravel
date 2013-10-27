
    <link rel="stylesheet" href="{{ URL::asset('assets/css/jquery.gritter.css') }}" />
    <script src="{{ URL::asset('assets/js/jquery.gritter.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            @foreach($gritter as $msg)
                gritter('{{ $msg['type'] }}', '{{ $msg['title'] }}', '{{ $msg['message'] }}')
            @endforeach

            function gritter(key, title, message)
            {
                var gritterClass = 'gritter-' + key + ' gritter-light'
                $.gritter.add({
                    // (string | mandatory) the heading of the notification
                    title: title,
                    // (string | mandatory) the text inside the notification
                    text: message,
                    class_name: gritterClass
                });
            }

        });
    </script>