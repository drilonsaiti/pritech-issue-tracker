@extends('layouts.app')

@section('header', $issue->title)

@section('content')
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary-custom">Back</a>

        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-primary-custom">Edit</a>
        <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Delete this issue?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-secondary-custom text-danger">Delete</button>
        </form>
    </div>
    <div class="card-surface p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start">

            <div>
                <p class="mb-2">{{ $issue->description ?? 'No description.' }}</p>
                <span class="badge badge-{{ $issue->status->value }} me-2">{{ $issue->status->label() }}</span>
                <span class="text-muted small">Priority: {{ $issue->priority->label() }}</span>
                <span class="text-muted small ms-3">Due: {{ $issue->due_date?->format('M d, Y') ?? '—' }}</span>
                <div class="text-muted small mt-1">
                    Project: <a href="{{ route('projects.show', $issue->project) }}">{{ $issue->project->name }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-surface p-4 mb-4" id="tags-section" data-issue-id="{{ $issue->id }}">
        <h6 class="mb-3">Tags</h6>
        <div id="tags-list" class="d-flex flex-wrap gap-2 mb-3">
            @foreach ($issue->tags as $tag)
                <span class="tag-badge badge bg-light text-dark border" data-tag-id="{{ $tag->id }}">
                    {{ $tag->name }}
                    <button type="button" class="btn-close btn-close-sm ms-1 detach-tag-btn"
                            style="font-size:.6rem;" data-tag-id="{{ $tag->id }}"></button>
                </span>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-secondary-custom" data-bs-toggle="modal"
                data-bs-target="#attachTagModal">
            + Add Tag
        </button>
    </div>

    <div class="card-surface p-4 mb-4" id="members-section" data-issue-id="{{ $issue->id }}">
        <h6 class="mb-3">Members</h6>
        <div id="members-list" class="d-flex flex-wrap gap-2 mb-3">
            @foreach ($issue->members as $member)
                <span class="member-badge badge bg-light text-dark border d-inline-flex align-items-center gap-1"
                      data-member-id="{{ $member->id }}">
                {{ $member->name }}
                <button type="button" class="btn-close detach-member-btn" style="font-size:.55rem;"
                        data-member-id="{{ $member->id }}" data-tag-id="{{ $tag->id }}"></button>
            </span>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-secondary-custom" data-bs-toggle="modal"
                data-bs-target="#attachMemberModal">
            + Assign Member
        </button>
    </div>

    <div class="card-surface p-4" id="comments-section" data-issue-id="{{ $issue->id }}">
        <h6 class="mb-3">Comments</h6>
        <div id="comments-list" class="mb-3"></div>
        <div id="comments-pagination" class="mb-3"></div>

        <form id="comment-form">
            <input type="hidden" name="issue_id" value="{{$issue->id}}">
            <div class="mb-2">
                <input type="text" value="{{auth()->user()->name}}" name="author_name" class="form-control" placeholder="Your name">
                <div class="text-danger small mt-1" data-error="author_name"></div>
            </div>
            <div class="mb-2">
                <textarea name="body" class="form-control" rows="2" placeholder="Write a comment..."></textarea>
                <div class="text-danger small mt-1" data-error="body"></div>
            </div>
            <button type="submit" class="btn btn-primary-custom btn-sm">Post Comment</button>
        </form>
    </div>

    <div class="modal fade" id="attachTagModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attach a Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <select id="tag-select" class="form-select">
                        <option value="">Select a tag...</option>
                        @foreach($tags as $tag)
                            <option value="{{$tag->id}}">{{$tag->name}}</option>
                        @endforeach
                    </select>
                    <div class="text-danger small mt-2" id="attach-tag-error"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="confirm-attach-tag">Attach</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attachMemberModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign a Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select id="member-select" class="form-select">
                        <option value="">Select a user...</option>
                        @foreach (\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger small mt-2" id="attach-member-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="confirm-attach-member">Assign</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const issueId = document.getElementById('tags-section').dataset.issueId;
            const tagsList = document.getElementById('tags-list');

            document.getElementById('confirm-attach-tag').addEventListener('click', function () {
                const tagSelect = document.getElementById('tag-select');
                const tagId = tagSelect.value;
                const error = document.getElementById('attach-tag-error');
                error.textContent = '';

                if (!tagId) {
                    error.textContent = 'Please select a tag.';
                    return;
                }

                fetch(`/issues/${issueId}/tags`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({tag_id: tagId})
                })
                    .then(async (res) => {
                        const data = await res.json();
                        if (!res.ok) throw data;
                        return data;
                    })
                    .then((data) => {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-light text-dark border d-inline-flex align-items-center gap-1';
                        badge.dataset.tagId = data.tag.id;
                        badge.innerHTML = `${data.tag.name} <button type="button" class="btn-close detach-tag-btn" style="font-size:.55rem;" data-tag-id="${data.tag.id}"></button>`;
                        tagsList.appendChild(badge);

                        tagSelect.value = '';
                        bootstrap.Modal.getInstance(document.getElementById('attachTagModal')).hide();
                    })
                    .catch((err) => {
                        error.textContent = err.message || 'Something went wrong.';
                    });
            })

            tagsList.addEventListener('click', function (e) {
                if (!e.target.classList.contains('detach-tag-btn')) return;

                const tagId = e.target.dataset.tagId;
                const badge = e.target.closest('.tag-badge');

                fetch(`/issues/${issueId}/tags/${tagId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                })
                    .then((res) => {
                        if (!res.ok) throw new Error('Failed to detach tag.');
                        badge.remove();
                    })
                    .catch((err) => alert(err.message));
            });

            const commentsList = document.getElementById('comments-list');
            const commentsPagination = document.getElementById('comments-pagination');
            const commentForm = document.getElementById('comment-form');

            function renderComment(comment) {
                const div = document.createElement('div');
                div.className = 'border-bottom pb-2 mb-2 comment-item';
                div.dataset.commentId = comment.id;

                div.innerHTML = `
        <div class="fw-semibold small">${comment.author_name}</div>

        ${comment.is_owner ? `
            <div>
                <button
                    class="btn btn-sm btn-link text-danger delete-comment-btn p-0"
                    data-comment-id="${comment.id}">
                    Delete
                </button>
            </div>
        ` : ''}

        <div class="small text-muted">${new Date(comment.created_at).toLocaleString()}</div>
        <div>${comment.body}</div>
    `;

                return div;
            }

            function loadComments(page = 1) {
                fetch(`/issues/${issueId}/comments?page=${page}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then((res) => res.json())
                    .then((json) => {
                        const paginator = json.data;
                        commentsList.innerHTML = '';

                        if (paginator.data.length === 0) {
                            commentsList.innerHTML = '<p class="text-muted small">No comments yet.</p>';
                        } else {
                            paginator.data.forEach((comment) => {
                                commentsList.appendChild(renderComment(comment))
                            })
                        }

                        commentsPagination.innerHTML = '';
                        if (paginator.last_page > 1) {
                            for (let i = 1; i <= paginator.last_page; i++) {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'btn btn-sm ' + (i === paginator.current_page ? 'btn-primary-custom' : 'btn-secondary-custom');
                                btn.textContent = i;
                                btn.addEventListener('click', () => loadComments(i));
                                commentsPagination.appendChild(btn);
                            }
                        }
                    })
                    .catch(() => {
                        commentsList.innerHTML = '<p class="text-danger small">Failed to load comments.</p>';
                    });
            }

            commentsList.addEventListener('click', function (e) {
                if (!e.target.classList.contains('delete-comment-btn')) return;

                const commentId = e.target.dataset.commentId;
                if (!confirm('Delete this comment?')) return;

                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                })
                    .then((res) => {
                        if (!res.ok) throw new Error('Failed to delete.');
                        e.target.closest('.comment-item').remove();
                    })
                    .catch((err) => alert(err.message));
            });

            commentForm.addEventListener('submit', function (e) {
                e.preventDefault();

                commentForm.querySelectorAll('[data-error]').forEach(el => el.textContent = '');
                const formData = new FormData(commentForm);
                const payload = Object.fromEntries(formData.entries());

                fetch('/comments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                })
                    .then(async (res) => {
                        const data = await res.json();
                        if (!res.ok) throw {status: res.status, data};
                        return data;
                    })
                    .then((response) => {
                        const newCommentElement = renderComment(response.data);
                        if (commentsList.firstChild && commentsList.firstChild.tagName === 'P') {
                            commentsList.innerHTML = '';
                        }
                        commentsList.insertBefore(newCommentElement, commentsList.firstChild);

                        commentForm.reset();
                        document.querySelector('[name=issue_id]').value = issueId;
                    })
                    .catch((err) => {
                        if (err.status === 422 && err.data.errors) {
                            Object.entries(err.data.errors).forEach(([field, messages]) => {
                                const el = commentForm.querySelector(`[data-error="${field}"]`);
                                if (el) el.textContent = messages[0];
                            });
                        } else {
                            alert('Something went wrong posting your comment.');
                        }
                    });
            })

            loadComments();

            const membersList = document.getElementById('members-list');

            document.getElementById('confirm-attach-member').addEventListener('click', function () {
                const memberSelect = document.getElementById('member-select');
                const userId = memberSelect.value;
                const error = document.getElementById('attach-member-error');
                error.textContent = '';

                if (!userId) {
                    error.textContent = 'Please select a user.';
                    return;
                }

                fetch(`/issues/${issueId}/members`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({user_id: userId}),
                })
                    .then(async (res) => {
                        const data = await res.json();
                        if (!res.ok) throw data;
                        return data;
                    })
                    .then((data) => {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-light text-dark border d-inline-flex align-items-center gap-1';
                        badge.dataset.memberId = data.member.id;
                        badge.innerHTML = `${data.member.name} <button type="button" class="btn-close detach-member-btn" style="font-size:.55rem;" data-member-id="${data.member.id}"></button>`;
                        membersList.appendChild(badge);


                        memberSelect.value = '';
                        bootstrap.Modal.getInstance(document.getElementById('attachMemberModal')).hide();
                    })
                    .catch((err) => {
                        error.textContent = err.message || 'Something went wrong.';
                    });
            });

            membersList.addEventListener('click', function (e) {
                if (!e.target.classList.contains('detach-member-btn')) return;

                const userId = e.target.dataset.memberId;
                const badge = e.target.closest('.member-badge');

                fetch(`/issues/${issueId}/members/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                })
                    .then((res) => {
                        if (!res.ok) throw new Error('Failed to remove member.');
                        badge.remove();
                    })
                    .catch((err) => alert(err.message));
            });
        });


    </script>
@endsection
