<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f4f6f9;
    }
    .welcome-container {
      text-align: center;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .welcome-container h1 {
      font-size: 2.5rem;
      color: #333;
    }
    .welcome-container p {
      font-size: 1rem;
      color: #666;
      margin: 10px 0 20px;
    }
    .welcome-container a {
      text-decoration: none;
      color: #fff;
      background-color: #007bff;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 1rem;
    }
    .welcome-container a:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="welcome-container">
    <h1>Welcome to Asset Kominfo</h1>
    <p>Manage your assets efficiently and effectively.</p>
    <a href="<?= base_url('/dashboard') ?>"><i class="fas fa-arrow-right"></i> Go to Dashboard</a>
  </div>
</body>

</html></html>