{{--
    Reusable stat-tile grid for the character PDF export, built with a real
    <table> (not flexbox/grid) since that's what dompdf renders reliably.

    $items: list of ['label' => string, 'value' => string, 'hint' => ?string, 'list' => ?array]
            When 'list' is present it's rendered as bullet lines instead of 'value'.
    $columns: how many tiles per row.
--}}
@php $rows = array_chunk($items, $columns); @endphp
<table class="pdf-grid">
    <tbody>
        @foreach ($rows as $row)
            <tr>
                @foreach ($row as $item)
                    <td class="pdf-stat" style="width: {{ number_format(100 / $columns, 4) }}%;">
                        <div class="pdf-stat-label">{{ $item['label'] }}</div>
                        @if (isset($item['list']))
                            <ul class="pdf-stat-list">
                                @foreach ($item['list'] as $line)
                                    <li>{{ $line }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="pdf-stat-value">{{ $item['value'] }}</div>
                        @endif
                        @if (! empty($item['hint']))
                            {{-- Built by the caller (pdf.blade.php), not user input — safe to render raw so it can include the proficiency dot markup. --}}
                            <div class="pdf-stat-hint">{!! $item['hint'] !!}</div>
                        @endif
                    </td>
                @endforeach
                @for ($i = count($row); $i < $columns; $i++)
                    <td class="pdf-stat-filler" style="width: {{ number_format(100 / $columns, 4) }}%;"></td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
