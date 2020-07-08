<!-- Movimientos -->
    <div class="card card-accent-info">
        <div class="card-header h4" role="tab">
            <a href="#" data-toggle="collapse" data-target="#collapseMovimientos">
                Movimientos
            </a>
            <div class="card-header-actions">
                <a class="card-header-action btn-minimize" href="#" data-toggle="collapse" data-target="#collapseMovimientos" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                    <i class="icon-arrow-up"></i>
                </a>
            </div>
        </div>
        <div id="collapseMovimientos" class="collapse show">
            <div class="card-body py-2">
                @isset($movements)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha salida</th>
                                <th>Fecha entrada</th>
                                <th>Institución</th>
                                <th>Ubicación / Exposición</th>
                                <th>Sede</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movements as $movement)
                                @php
                                    $arrivalDate = "";
                                    if(isset($movement->arrival_information) && !empty($movement->arrival_information)){
                                        $arrivalInformation = json_decode($movement->arrival_information);
                                        // se recorre la informacion de regreso
                                        foreach ($arrivalInformation as $datos) {
                                            // se verifica si la pieza esta entre los datos actuales
                                            if((is_array($datos->pieces) && in_array($piece->id, $datos->pieces)) || ($piece->id == $datos->pieces)){
                                                $arrivalDate = \Carbon\Carbon::createFromFormat('Y-m-d', $datos->arrival_date)->locale('es_MX')->isoFormat('LL');
                                                break;
                                            }
                                        }
                                    } elseif(!empty($movement->arrival_date)){
                                        $arrivalDate = $movement->arrival_date->locale('es_MX')->isoFormat('LL');
                                    }
                                @endphp
                                <tr>
                                    <td>{!! !empty($movement->departure_date) ? $movement->departure_date->locale('es_MX')->isoFormat('LL') : '' !!}</td>
                                    <td>{!! $arrivalDate !!}</td>
                                    <td>{{!is_null($movement->institutions) ? implode(", ", $movement->institutions->pluck('name')->toArray()) : '-'}}</td>
                                    <td>{{$movement->exhibition['name']}}</td>
                                    <td>{{!is_null($movement->venue) ? implode(", ", $movement->venue->pluck('name')->toArray()) : '-'}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning mt-3">No se han registrado movimientos</div>
                @endisset
            </div>
        </div>
    </div>
