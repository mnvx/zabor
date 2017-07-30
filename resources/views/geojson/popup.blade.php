<b>Участок {{ $number }}</b><br/>

<ul>
@foreach ($data as $item)
    <li>
        <div class="row">
            <div class="col m3">
                @if (isset($item['user']))
                <a href="{{ url('user/profile/' . (isset($item['user']) ? $item['user']->id : null)) }}">
                    <div class="avatar-large center-in-parent">
                        <div
                                class="responsive-circle center-in-parent"
                                style="background-image: url({{ $item['user']->avatar && $item['user']->avatar->path
                                    ? $item['user']->avatar->path
                                    : 'plugins/clake/userextended/assets/img/default_user.png'
                                }})"></div>
                    </div>
                </a>
                @else
                    <div class="avatar-large center-in-parent">
                        <div
                                class="responsive-circle center-in-parent"
                                style="background-image: url({{ 'plugins/clake/userextended/assets/img/default_user.png' }})"></div>
                    </div>
                @endif
            </div>
            <div class="col m9">
                @if (isset($item['user']))
                    <a href="{{ url('user/profile/' . (isset($item['user']) ? $item['user']->id : null)) }}">
                        {{ $item['name'] }}
                    </a>
                @else
                    {{ $item['name'] }}
                @endif
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
            </div>
        </div>
    </li>
@endforeach
</ul>
