@extends('admin.layouts.app')

@section('content')
<style>
.section-video-sorter {
    max-width: 980px;
    margin-top: 20px;
}
.section-video-row {
    display: flex;
    align-items: center;
    gap: 18px;
    min-height: 108px;
    margin-bottom: 10px;
    padding: 10px 16px;
    background: #fff;
    border: 1px solid #e6e6e6;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
    cursor: grab;
    transition: transform .15s ease, box-shadow .15s ease, opacity .15s ease;
}
.section-video-row:active { cursor: grabbing; }
.section-video-row.dragging { opacity: .45; }
.section-video-row.drag-over {
    transform: translateY(2px);
    box-shadow: 0 5px 18px rgba(0,0,0,.12);
    border-color: #9c27b0;
}
.drag-handle { color: #9c27b0; cursor: grab; }
.order-number {
    width: 34px;
    height: 34px;
    line-height: 34px;
    text-align: center;
    border-radius: 50%;
    background: #f3e5f5;
    color: #7b1fa2;
    font-weight: 700;
    flex: 0 0 34px;
}
.poster-wrap {
    width: 64px;
    height: 96px;
    overflow: hidden;
    border-radius: 4px;
    background: #111;
    flex: 0 0 64px;
}
.poster-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.video-info { flex: 1 1 auto; min-width: 0; }
.video-info strong { display: block; font-size: 17px; }
.video-info small { color: #777; }
.move-buttons { flex: 0 0 auto; white-space: nowrap; }
.move-buttons .material-icons { font-size: 24px; }
@media (max-width: 767px) {
    .section-video-row { gap: 10px; padding: 8px; }
    .poster-wrap { width: 48px; height: 72px; flex-basis: 48px; }
    .video-info strong { font-size: 14px; }
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="card-title">Arrange {{ $section->name }}</h4>
                        <p class="text-muted">
                            Drag movies into the exact order you want them to appear in the {{ $section->name }} carousel.
                            The website and mobile app will use the same order.
                        </p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('sections.index') }}" class="btn btn-default btn-sm">
                            <i class="material-icons">arrow_back</i> Back to Sections
                        </a>
                    </div>
                </div>

                <div id="order-status" class="alert" style="display:none; margin-top:15px;"></div>

                @if($section->videos->count())
                    <div id="sortable-videos" class="section-video-sorter">
                        @foreach($section->videos as $video)
                            <div class="section-video-row" draggable="true" data-video-id="{{ $video->id }}">
                                <div class="drag-handle" title="Drag to move">
                                    <i class="material-icons">drag_indicator</i>
                                </div>
                                <div class="order-number">{{ $loop->iteration }}</div>
                                <div class="poster-wrap">
                                    <img src="{{ $video->tn_poster }}" alt="{{ $video->title }}">
                                </div>
                                <div class="video-info">
                                    <strong>{{ $video->title }}</strong>
                                    <small>
                                        {{ ($video->content_type ?? 'movie') == 'series' ? 'Series' : 'Movie' }}
                                    </small>
                                </div>
                                <div class="move-buttons">
                                    <button type="button" class="btn btn-simple btn-xs move-up" title="Move up">
                                        <i class="material-icons">keyboard_arrow_up</i>
                                    </button>
                                    <button type="button" class="btn btn-simple btn-xs move-down" title="Move down">
                                        <i class="material-icons">keyboard_arrow_down</i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">There are no movies attached to this section yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection



@section('inline-scripts')
(function () {
    var container = document.getElementById('sortable-videos');
    if (!container) return;

    var dragged = null;
    var savingTimer = null;
    var saveInFlight = false;
    var saveAgain = false;

    function rows() {
        return Array.prototype.slice.call(container.querySelectorAll('.section-video-row'));
    }

    function refreshNumbers() {
        rows().forEach(function (row, index) {
            var number = row.querySelector('.order-number');
            if (number) number.textContent = index + 1;
        });
    }

    function showStatus(message, type) {
        var status = document.getElementById('order-status');
        status.className = 'alert alert-' + type;
        status.textContent = message;
        status.style.display = 'block';
    }

    function queueSave() {
        refreshNumbers();
        clearTimeout(savingTimer);
        showStatus('Saving order...', 'info');
        savingTimer = setTimeout(saveOrder, 250);
    }

    function saveOrder() {
        if (saveInFlight) {
            saveAgain = true;
            return;
        }

        saveInFlight = true;
        var videoIds = rows().map(function (row) {
            return parseInt(row.getAttribute('data-video-id'), 10);
        });

        fetch(@json(route('sections.videos.order.update', ['section' => $section->id])), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': @json(csrf_token())
            },
            body: JSON.stringify({ video_ids: videoIds })
        })
        .then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok) throw new Error(data.message || 'Could not save movie order.');
                return data;
            });
        })
        .then(function (data) {
            showStatus(data.message || 'Movie order saved.', 'success');
        })
        .catch(function (error) {
            showStatus(error.message || 'Could not save movie order. Refresh and try again.', 'danger');
        })
        .then(function () {
            saveInFlight = false;
            if (saveAgain) {
                saveAgain = false;
                saveOrder();
            }
        });
    }

    container.addEventListener('dragstart', function (event) {
        var row = event.target.closest('.section-video-row');
        if (!row) return;
        dragged = row;
        row.classList.add('dragging');
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', row.getAttribute('data-video-id'));
    });

    container.addEventListener('dragover', function (event) {
        event.preventDefault();
        var target = event.target.closest('.section-video-row');
        rows().forEach(function (row) { row.classList.remove('drag-over'); });
        if (!dragged || !target || target === dragged) return;

        target.classList.add('drag-over');
        var rect = target.getBoundingClientRect();
        var after = event.clientY > rect.top + (rect.height / 2);
        container.insertBefore(dragged, after ? target.nextSibling : target);
    });

    container.addEventListener('drop', function (event) {
        event.preventDefault();
        rows().forEach(function (row) { row.classList.remove('drag-over'); });
        queueSave();
    });

    container.addEventListener('dragend', function () {
        if (dragged) dragged.classList.remove('dragging');
        rows().forEach(function (row) { row.classList.remove('drag-over'); });
        dragged = null;
    });

    container.addEventListener('click', function (event) {
        var button = event.target.closest('.move-up, .move-down');
        if (!button) return;
        var row = button.closest('.section-video-row');
        if (!row) return;

        if (button.classList.contains('move-up') && row.previousElementSibling) {
            container.insertBefore(row, row.previousElementSibling);
            queueSave();
        }

        if (button.classList.contains('move-down') && row.nextElementSibling) {
            container.insertBefore(row.nextElementSibling, row);
            queueSave();
        }
    });

    refreshNumbers();
})();
@stop
