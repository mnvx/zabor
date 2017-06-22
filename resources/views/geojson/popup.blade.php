<b>Участок {{ $number }}</b><br/>

<ul>
@foreach ($data as $item)
    <li>{{ $item['name'] }}:
        <ul>
            @if ($item['phone'])
                <li>Телефон: {!! str_replace(',', '<br/>', $item['phone']) !!}</li>
            @endif
            @if ($item['email'])
                <li>E-mail:
                @foreach (explode(',', $item['email']) as $email)
                    <a href="mailto:{{ trim($email) }}">{{ $email }}</a>
                @endforeach
                </li>
            @endif
        </ul>
    </li>
@endforeach
</ul>
