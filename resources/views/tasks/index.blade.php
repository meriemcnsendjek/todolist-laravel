<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma To-Do List</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background dégradé moderne */
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Container pour rester au-dessus du background */
        .container {
            position: relative;
            z-index: 1;
        }

        /* Style des cartes */
        .card-task {
            transition: 0.3s;
            border-radius: 15px;
            border: none;
        }

        .card-task:hover {
            transform: scale(1.02);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        /* Tâches complétées */
        .completed {
            text-decoration: line-through;
            color: #d1d1d1;
        }

        /* Boutons stylés */
        .btn-outline-success {
            font-weight: bold;
        }

        /* Badge dynamique */
        .badge-status {
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="container mt-5">

    <h1 class="mb-4 text-center text-white">Ma To-Do List</h1>

    <!-- Formulaire d'ajout -->
    <form id="add-task-form" action="/tasks" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="title" class="form-control" placeholder="Nouvelle tâche" required>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
    </form>

    <!-- Liste des tâches -->
    <div class="row g-3" id="tasks-container">
        @foreach($tasks as $task)
            <div class="col-md-6 col-lg-4 task-card" data-id="{{ $task->id }}">
                <div class="card card-task">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="task-title {{ $task->is_completed ? 'completed' : '' }}">
                            {{ $task->title }}
                        </span>
                        <div class="btn-group">
                            <!-- Bouton compléter -->
                            <form action="/tasks/{{ $task->id }}" method="POST" class="complete-form">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm {{ $task->is_completed ? 'btn-success' : 'btn-outline-success' }}">
                                    {{ $task->is_completed ? '✔' : '❌' }}
                                </button>
                            </form>

                            <!-- Bouton supprimer -->
                            <form action="/tasks/{{ $task->id }}" method="POST" class="delete-form ms-1">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">🗑</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <span class="badge badge-status {{ $task->is_completed ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $task->is_completed ? 'Complétée' : 'En cours' }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Petit JS pour animation instantanée -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Completer tâche sans reload
        document.querySelectorAll('.complete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = this.querySelector('button');
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value
                    }
                }).then(() => {
                    const card = this.closest('.task-card');
                    const title = card.querySelector('.task-title');
                    const badge = card.querySelector('.badge-status');

                    if (btn.textContent === '❌') {
                        btn.textContent = '✔';
                        btn.classList.remove('btn-outline-success');
                        btn.classList.add('btn-success');
                        title.classList.add('completed');
                        badge.textContent = 'Complétée';
                        badge.classList.remove('bg-warning', 'text-dark');
                        badge.classList.add('bg-success');
                    } else {
                        btn.textContent = '❌';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-outline-success');
                        title.classList.remove('completed');
                        badge.textContent = 'En cours';
                        badge.classList.remove('bg-success');
                        badge.classList.add('bg-warning', 'text-dark');
                    }
                });
            });
        });

        // Supprimer tâche instantanément
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value
                    }
                }).then(() => {
                    this.closest('.task-card').remove();
                });
            });
        });
    });
</script>

</body>
</html>
