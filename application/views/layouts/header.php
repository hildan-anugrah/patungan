<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Patungan'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 0;
        }

        .navbar-custom {
            background-color: #2c3e50 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-custom .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
        }

        .navbar-custom .navbar-text {
            color: white !important;
            margin-right: 1rem;
        }

        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 1.5rem;
        }

        .card-title {
            color: #2c3e50;
            font-weight: 600;
        }

        .btn-custom-primary {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-custom-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-custom-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .btn-custom-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .btn-custom-success {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        .btn-custom-success:hover {
            background-color: #229954;
            border-color: #229954;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-belum-bayar {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-sudah-bayar {
            background-color: #d4edda;
            color: #155724;
        }

        .auth-container {
            max-width: 400px;
            margin: 3rem auto;
        }

        .auth-card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
        }

        .auth-card h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .auth-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-link a {
            color: #3498db;
            text-decoration: none;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 3rem;
        }

        .table thead th {
            background-color: #34495e;
            color: white;
            border: none;
        }

        .btn-sm-custom {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body class="d-flex flex-column" style="min-height: 100vh;">
