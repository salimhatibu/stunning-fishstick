<?php
// public/users.php
require_once __DIR__ . '/../includes/config.php';

$sql = "SELECT username, email, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task App - Registered Users</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .users-list {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            border: 2px solid #e9ecef;
        }

        .users-list h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-item {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .user-item:last-child {
            margin-bottom: 0;
        }

        .user-info h3 {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .user-info p {
            color: #666;
            font-size: 0.95rem;
        }

        .user-date {
            color: #999;
            font-size: 0.85rem;
            font-style: italic;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-links {
            text-align: center;
            margin-top: 30px;
        }

        .nav-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            margin: 0 15px;
        }

        .nav-links a:hover {
            color: #764ba2;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            .user-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .user-date {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registered Users</h1>
            <p>Meet our amazing community members</p>
        </div>

        <div class="content">
            <?php
            $userCount = 0;
            $users = [];
            
            if ($result && $result->num_rows > 0) {
                $userCount = $result->num_rows;
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
            }
            ?>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $userCount; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $userCount > 0 ? count(array_filter($users, function($user) { 
                        return !empty($user['email']); 
                    })) : 0; ?></div>
                    <div class="stat-label">Active Emails</div>
                </div>
            </div>

            <div class="users-list">
                <h2>
                    <span>👥</span>
                    Community Members
                </h2>

                <?php if ($userCount > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <div class="user-item">
                            <div class="user-info">
                                <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                                <p><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <?php if (isset($user['created_at'])): ?>
                                <div class="user-date">
                                    Joined: <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No users yet</h3>
                        <p>Be the first to join our community!</p>
                        <a href="index.php" class="btn">Sign Up Now</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="nav-links">
                <a href="index.php">← Back to Sign Up</a>
                <a href="mail.php">Test Email</a>
            </div>
        </div>
    </div>
</body>
</html>
