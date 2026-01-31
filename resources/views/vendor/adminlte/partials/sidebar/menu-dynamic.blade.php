@inject('url', 'Illuminate\Support\Facades\URL')

@foreach($menuSistema as $menu)
    <li class="nav-item {{ count($menu->submenus) > 0 ? 'has-treeview' : '' }}">
        <a href="{{ $menu->rota === '#' ? '#' : $url->signedRoute($menu->rota) }}" class="nav-link">
            <i class="nav-icon {{ $menu->icone }}"></i>
            <p>
                {{ $menu->descricao }}
                @if(count($menu->submenus) > 0)
                    <i class="right fas fa-angle-left"></i>
                @endif
            </p>
        </a>
        @if(count($menu->submenus) > 0)
            <ul class="nav nav-treeview">
                @foreach($menu->submenus as $submenu)
                    <li class="nav-item">
                        <a href="{{ $url->signedRoute($submenu->rota) }}" class="nav-link">
                            <i class="nav-icon {{ $submenu->icone ?: 'far fa-circle' }}"></i>
                            <p>{{ $submenu->descricao }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach
