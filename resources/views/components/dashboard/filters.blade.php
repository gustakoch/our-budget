<div class="filters">
    <div class="years">
        <strong>Ano</strong>
        <div class="months-years-row">
            @foreach ($years as $yearItem)
                <input type="radio" name="year" id="year-{{ $yearItem->year }}" value="{{ $yearItem->year }}" {{ $yearItem->year == $year ? 'checked' : '' }}>
                <label for="year-{{ $yearItem->year }}">{{ $yearItem->year }}</label>
            @endforeach
        </div>
    </div>
    <div class="months">
        <strong>Meses</strong>
        <div class="months-years-row">
            @foreach ($months as $i => $_month)
                <input type="radio" name="month" id="month-{{ $_month->id }}" value="{{ $_month->id }}" {{ $_month->id == $month ? 'checked' : '' }}>
                <label for="month-{{ $_month->id }}">{{ $_month->description }}</label>
            @endforeach
        </div>
    </div>
</div>
