
    @foreach($alerts as $alert)
        <div class="alert alert-{{ $alert['type'] }}">
            <button type="button" class="close" data-dismiss="alert">
                <i class="icon-remove"></i>
            </button>

            <strong>
                <i class="icon-remove"></i>
                {{ $alert['title'] }}
            </strong>

            {{ $alert['message'] }}
            <br>
        </div>
    @endforeach
