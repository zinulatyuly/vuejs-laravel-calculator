<h1>{{ $title }}</h1>
<h2>Input data:</h2>
<h4>Parameters of the building and its territory:</h4>
<ul style="list-style: none outside;">
    <li>Building type: <em>{{ $building_name }}</em></li>
    @foreach ($building_params as $param)
        <li>{{ $param['name'] }} <em>{{ $param['value'] }}</em></li>
    @endforeach
</ul>

<h4>Parameters of the lightning conductor:</h4>
<ul style="list-style: none outside;">
    <li>Lightning conductor: <em>{{ $conductor_name }}</em></li>
    @foreach ($conductor_params as $param)
        <li>{{ $param['name'] }} <em>{{ $param['value'] }}</em></li>
    @endforeach
</ul>

<h4>Kinds of constructions, protection zone types and categories of lightning protection:</h4>
<ul style="list-style: none outside;">
    <li>Kind of construction: <em>{{ $structure_params['kind']['label'] }}</em></li>
    <li>Placement: <em>{{ $structure_params['location']['label'] }}</em></li>
    <li>Type of protection zone: <em>{{ strtoupper($structure_params['zone']) }}</em></li>
    <li>Category of lightning protection: <em>{{ $structure_params['category'] }}</em></li>
</ul>

<h2>Results:</h2>
<ul style="list-style: none outside;">
    @foreach ($conductor_results as $result)
        @if ( isset($result['group']) )
            <li>
                <h4>{{ $result['name'] }}</h4>
                <ul>
                    @foreach ( $result['group'] as $value )
                        <li>{{ $value['name'] }} <strong><em>{{ $value['value'] }}</em></strong></li>
                    @endforeach
                </ul>
            </li>
        @else
            <li>{{ $result['name'] }} <strong><em>{{ $result['value'] }}</em></strong></li>
        @endif
    @endforeach
    @foreach ($building_results as $result)
        @if ( isset($result['group']) )
            <li>
                <h4>{{ $result['name'] }}</h4>
                <ul>
                    @foreach ( $result['group'] as $value )
                        <li>{{ $value['name'] }} <strong><em>{{ $value['value'] }}</em></strong></li>
                    @endforeach
                </ul>
            </li>
        @else
            <li>{{ $result['name'] }} <strong><em>{{ $result['value'] }}</em></strong></li>
        @endif
    @endforeach
</ul>