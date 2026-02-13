@extends('adminlte::page')

@section('title', 'Personalizar Caixa de Som')

@section('content')
    <div class="row">
        <div class="col-md-4">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personalização</h3>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label>Cor da caixa</label>
                        <input type="color" id="corCaixa" class="form-control" value="#7c4aac">
                    </div>

                    <div class="form-group">
                        <label>Cor dos pés</label>
                        <input type="color" id="corPes" class="form-control" value="#111111">
                    </div>

                    <div class="form-group">
                        <label>Acabamento</label>
                        <select id="acabamento" class="form-control">
                            <option value="solid">Cor sólida</option>
                            <option value="gradient">Degradê</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-8 text-center">

            {{-- SVG INLINE --}}
            <svg id="produto" width="360" height="640" viewBox="0 0 360 640">
                <defs>
                    <!-- Degradê da pedra -->
                    <linearGradient id="gradPedra" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#e6e6e6"/>
                        <stop offset="100%" stop-color="#cfcfcf"/>
                    </linearGradient>

                    <!-- Sombra -->
                    <filter id="sombra" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="12" stdDeviation="18" flood-opacity="0.35"/>
                    </filter>
                </defs>

                <!-- PEDRA -->
                <g id="pedra" filter="url(#sombra)">
                    <!-- Face frontal -->
                    <rect x="80" y="120" width="200" height="340" rx="6"
                          fill="url(#gradPedra)"/>

                    <!-- Topo -->
                    <polygon points="80,120 110,90 310,90 280,120"
                             fill="#eeeeee"/>

                    <!-- Lateral -->
                    <polygon points="280,120 310,90 310,430 280,460"
                             fill="#d5d5d5"/>
                </g>

                <!-- ESTRUTURA METÁLICA -->
                <g id="estrutura" fill="#ffffff">
                    <!-- Moldura superior -->
                    <rect x="70" y="190" width="220" height="60" rx="4"/>
                    <rect x="95" y="205" width="170" height="30" fill="#222"/>

                    <!-- Moldura inferior -->
                    <rect x="70" y="450" width="220" height="50" rx="4"/>

                    <!-- Base -->
                    <rect x="60" y="500" width="240" height="40" rx="4"/>
                </g>
            </svg>


        </div>
    </div>
@endsection

@section('js')
    <script>
        const corpo = document.getElementById('corpo');
        const pes = document.querySelectorAll('.pe');

        const corCaixa = document.getElementById('corCaixa');
        const corPes = document.getElementById('corPes');
        const acabamento = document.getElementById('acabamento');

        function atualizarCaixa() {
            if (acabamento.value === 'gradient') {
                corpo.setAttribute('fill', 'url(#gradCorpo)');
            } else {
                corpo.setAttribute('fill', corCaixa.value);
            }
        }

        corCaixa.addEventListener('input', () => {
            acabamento.value = 'solid';
            atualizarCaixa();
        });

        acabamento.addEventListener('change', atualizarCaixa);

        corPes.addEventListener('input', () => {
            pes.forEach(pe => pe.setAttribute('fill', corPes.value));
        });
    </script>
@endsection
