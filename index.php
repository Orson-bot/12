<?php
  $result = '';
  $equation = '';
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $display = $_POST['display'] ?? '0';
    $equation = $_POST['equation'] ?? '';
    $button = $_POST['button'] ?? '';
    
    if ($button === '=') {
      $equation = preg_replace('/[^0-9+\-.*\/().]/', '', $equation);
      try {
        eval('$result = ' . $equation . ';');
        $display = $result;
        $equation = $result;
      } catch (Exception $e) {
        $display = 'Error';
        $equation = '';
      }
    } elseif ($button === 'C') {
      $display = '0';
      $equation = '';
    } elseif (in_array($button, ['+', '-', '*', '/'])) {
      $equation .= ' ' . $button . ' ';
      $display = '0';
    } else {
      if ($display === '0' && $button !== '.') {
        $display = $button;
      } else {
        $display .= $button;
      }
      $equation .= $button;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JakHub Calculator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: black;
            font-family: Arial, sans-serif;
        }

        .calculator {
            background-color: #18181b;
            padding: 1.5rem;
            border-radius: 0.75rem;
            width: 320px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .logo {
            color: #ff9000;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .display {
            background-color: #27272a;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .equation {
            color: #9ca3af;
            font-size: 0.875rem;
            height: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .result {
            color: white;
            font-size: 1.875rem;
            font-weight: bold;
            text-align: right;
            width: 100%;
            background: transparent;
            border: none;
            outline: none;
        }

        .buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }

        button {
            background-color: #27272a;
            color: white;
            border: none;
            padding: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #3f3f46;
        }

        .equals {
            background-color: #ff9000;
        }

        .equals:hover {
            background-color: #ff7000;
        }

        .clear {
            grid-column: span 4;
            background-color: #dc2626;
            margin-top: 0.5rem;
        }

        .clear:hover {
            background-color: #b91c1c;
        }
    </style>
</head>
<body>
    <div class="calculator">
        <div class="header">
            <div class="logo">JaHub</div>
        </div>

        <form action="index.php" method="POST">
            <div class="display">
                <div class="equation"><?php echo htmlspecialchars($equation); ?></div>
                <input type="text" name="display" readonly value="<?php echo htmlspecialchars($display ?? '0'); ?>" class="result">
                <input type="hidden" name="equation" value="<?php echo htmlspecialchars($equation); ?>">
            </div>

            <div class="buttons">
                <?php
                $buttons = [
                    '7', '8', '9', '÷',
                    '4', '5', '6', '×',
                    '1', '2', '3', '-',
                    '0', '.', '=', '+',
                ];

                foreach ($buttons as $btn) {
                    $value = $btn;
                    if ($btn === '÷') $value = '/';
                    if ($btn === '×') $value = '*';
                    
                    $class = $btn === '=' ? 'equals' : '';
                    echo '<button type="submit" name="button" value="' . $value . '" class="' . $class . '">' . $btn . '</button>';
                }
                ?>
                
                <button type="submit" name="button" value="C" class="clear">Clear</button>
            </div>
        </form>
    </div>
</body>
</html>