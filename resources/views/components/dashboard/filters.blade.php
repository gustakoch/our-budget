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
            @foreach ($months as $i => $monthOfYear)
                <input type="radio" name="month" id="month-{{ $i +1 }}" value="{{ $i +1 }}" {{ $i + 1 == $month ? 'checked' : '' }}>
                <label for="month-{{ $i +1 }}">{{ $monthOfYear }}</label>
            @endforeach
        </div>
    </div>
</div>
