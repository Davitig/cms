@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="fa fa-file-text"></i>
                Notes
            </h1>
            <p class="description">Management of the notes</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file-text"></i>
                    <strong>Notes</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="notes-env">
        <div class="notes-header">
            <a class="btn btn-secondary btn-icon btn-icon-standalone" id="add-note">
                <i class="fa fa-file-text"></i>
                <span>New Note</span>
            </a>
            <button id="save-note" class="btn btn-secondary btn-icon-standalone dn" disabled>
                <i><b class="fa fa-save"></b></i>
                <span>{{ trans('general.save') }}</span>
            </button>
        </div>
        <div class="notes-list">
            <ul class="list-of-notes">
                @foreach ($items as $item)
                    <li data-id="{{$item->id}}">
                        <a href="#">
                            <strong>{{$item->title}}</strong>
                            <span>{{$item->description}}</span>
                        </a>
                        <div class="addon-buttons">
                            <button class="note-calendar" title="Add to calendar">
                                <i class="fa-calendar"></i>
                            </button>
                        </div>
                        <button class="note-close" title="{{trans('general.delete')}}">
                            <i class="fa-close"></i>
                        </button>
                        <div class="content">{!!$item->content!!}</div>
                    </li>
                @endforeach
                <!-- this will be automatically hidden when there are notes in the list -->
                <li class="no-notes">
                    There are no notes yet!
                </li>
            </ul>
            <div class="write-pad">
                <textarea class="form-control autogrow"></textarea>
            </div>
        </div>
    </div>
    @push('body.bottom')
        <script src="{{ asset('assets/libs/js/min/xenon-notes-min.js') }}"></script>
        <script type="text/javascript">
            $(function() {
                let id,
                    title       = '',
                    description = '',
                    content     = '';

                let listNotes = $('.list-of-notes');
                let notesList = $('.notes-header');
                let saveNote = $('#save-note');

                function updateNoteContent() {
                    id = listNotes.find('.current').data('id');

                    title       = xenonNotes.$currentNoteTitle.text();
                    description = xenonNotes.$currentNoteDescription.text();
                    content     = xenonNotes.$currentNoteContent.text();
                }

                $('.write-pad').on('keyup', 'textarea', function() {
                    saveNote.show().prop('disabled', false);
                    saveNote.find('.icon-var').removeClass('fa-spin fa-check').addClass('fa-save');

                    updateNoteContent();
                });

                // create/update note
                saveNote.on('click', function() {
                    let input = {'id':id, 'title':title, 'description':description, 'content':content, '_method':'put', '_token':"{{$csrfToken = csrf_token()}}"};

                    $.post("{{cms_route('notes.save')}}", input, function(newId) {
                        if (! id && newId) {
                            listNotes.find('.current').data('id', newId);
                        }

                        saveNote.find('.icon-var').removeClass('fa-spin fa-save').addClass('fa-check');
                        $('.text-danger', notesList).remove();
                    }, 'json').fail(function(xhr) {
                        saveNote.find('.icon-var').removeClass('fa-spin fa-save').addClass('fa-remove');
                        if (! xhr?.responseJSON) {
                            alert(xhr.responseText);
                            return;
                        }
                        $('.text-danger', notesList).remove();
                        if (xhr?.responseJSON?.message) {
                            notesList.append(
                                '<span class="text-danger">' + xhr?.responseJSON?.message + '</span>'
                            );
                        }
                    }).always(function() {
                        saveNote.delay(400).fadeOut(500);
                    });
                });

                // move note into the calendar
                listNotes.on('click', '.note-calendar', function() {
                    let note = $(this).closest('li');
                    note.addClass('current').siblings().removeClass('current');
                    xenonNotes.checkCurrentNote();

                    updateNoteContent();

                    // slice the title
                    content = content.split("\n").slice(1).join("\n");

                    let input = {'title':title, 'description':content, '_method':'put', '_token':"{{$csrfToken}}"};

                    $.post("{{cms_route('calendar.save')}}", input, function() {
                        note.find('.note-close').trigger('click');
                    }, 'json').fail(function(xhr) {
                        alert(xhr.responseText);
                    });
                });

                // delete note
                listNotes.on('click', '.note-close', function() {
                    let input = {'id':$(this).closest('li').data('id'), '_token':'{{$csrfToken}}', '_method': 'DELETE'};

                    $.post("{{cms_route('notes.destroy')}}", input, function() {}, 'json').fail(function(xhr) {
                        alert(xhr.responseText);
                    })
                });
            });
        </script>
    @endpush
@endsection
