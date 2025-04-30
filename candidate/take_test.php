<?php
session_start();
require '../config.php';
include '../alerts.php';
checkCandidateAuth();

// Check if test already taken
$stmt = $pdo->prepare("SELECT COUNT(*) FROM test_results WHERE candidate_id = ?");
$stmt->execute([$_SESSION['candidate_id']]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = "You have already taken the test!";
    header("Location: dashboard.php");
    exit();
}

// Initialize test questions if not already set
if (!isset($_SESSION['test_questions'])) {
    $stmt = $pdo->query("SELECT id FROM questions ORDER BY RAND() LIMIT 20");
    $_SESSION['test_questions'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $_SESSION['test_start_time'] = time();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        foreach ($_POST['answers'] as $questionId => $answer) {
            if (!in_array($questionId, $_SESSION['test_questions'])) {
                throw new Exception("Invalid question answered");
            }
            
            $stmt = $pdo->prepare("
                INSERT INTO test_results 
                (candidate_id, question_id, answer) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$_SESSION['candidate_id'], $questionId, $answer]);
        }
        
        unset($_SESSION['test_questions']);
        unset($_SESSION['test_start_time']);
        $pdo->commit();
        
        $_SESSION['success'] = "Test submitted successfully!";
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error submitting test: " . $e->getMessage();
        header("Location: take_test.php");
        exit();
    }
}

// Get questions for this test
$in = str_repeat('?,', count($_SESSION['test_questions']) - 1) . '?';
$stmt = $pdo->prepare("
    SELECT * 
    FROM questions 
    WHERE id IN ($in)
    ORDER BY FIELD(id, " . implode(',', $_SESSION['test_questions']) . ")
");
$stmt->execute($_SESSION['test_questions']);
$questions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <style>
        /* Hide test content for mobile/tablet users */
        .mobile-only {
            display: none;
        }
        .desktop-only {
            display: block;
        }
        @media (max-width: 768px) {
            /* Show the mobile message for mobile/tablet users */
            .desktop-only {
                display: none;
            }
            .mobile-only {
                display: block;
                text-align: center;
                padding: 20px;
                margin: 10% auto;
                font-size: 1.2rem;
                background-color: rgba(255, 223, 0, 0.9);
                border-radius: 8px;
                max-width: 90%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Mobile Alert Message -->
    <div class="mobile-only bg-yellow-500 text-white text-center p-4 rounded-lg">
        <p>Device not supported. Please log in from a PC to take the test.</p>
    </div>

    <!-- Test Content (Hidden for mobile users) -->
    <div class="desktop-only">
        <!-- Instructions Modal -->
        <div id="instructionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
                <h2 class="text-3xl font-bold mb-6 text-center">Test Instructions</h2>
                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                        <div class="text-2xl">📸</div>
                        <div>Camera access required for monitoring</div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                        <div class="text-2xl">⏱</div>
                        <div>30 minute time limit - Timer starts when you begin</div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                        <div class="text-2xl">🚫</div>
                        <div>No browser tab switching allowed</div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                        <div class="text-2xl">👤</div>
                        <div>Keep your face visible in the camera frame</div>
                    </div>
                </div>
                <button onclick="initializeTest()" 
                        class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors text-lg">
                    Start Test Now
                </button>
            </div>
        </div>

        <!-- Camera Preview -->
        <div id="cameraContainer" class="fixed bottom-4 right-4 w-64 h-48 bg-white rounded-xl shadow-2xl border-4 border-red-500 overflow-hidden hidden">
            <div class="bg-red-500 p-2 text-white text-sm font-bold">Live Camera Preview</div>
            <div id="my_camera" class="w-full h-full"></div>
        </div>

        <!-- Test Interface -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="text-xl font-bold">Online Assessment</div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-600"><?= htmlspecialchars($_SESSION['candidate_name']) ?></span>
                            <div class="w-auto h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 text-sm">&nbsp;&nbsp;ID: <?= $_SESSION['candidate_id'] ?> &nbsp;&nbsp;</span>
                            </div>
                        </div>
                        <div id="timer" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-mono">
                            30:00
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 py-8">
            <form method="POST" id="testForm" class="space-y-8">
                <?php foreach ($questions as $index => $q): ?>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-medium"><?= $index+1 ?></span>
                            </div>
                            <h3 class="text-lg font-semibold">
                                <?= htmlspecialchars($q['question']) ?>
                                <span class="text-sm text-gray-500 ml-2">(<?= strtoupper($q['type']) ?>)</span>
                            </h3>
                        </div>
                    </div>

                    <?php if ($q['type'] === 'mcq'): 
                        $options = json_decode($q['options']); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-2">
                            <?php foreach ($options as $i => $option): ?>
                            <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-blue-50 cursor-pointer">
                                <input type="radio" 
                                       name="answers[<?= $q['id'] ?>]" 
                                       value="<?= htmlspecialchars($option) ?>" 
                                       required
                                       class="form-radio h-5 w-5 text-blue-600 border-2">
                                <span class="text-gray-700"><?= htmlspecialchars($option) ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="mt-4">
                            <textarea name="answers[<?= $q['id'] ?>]" 
                                      class="w-full p-4 border rounded-lg font-mono text-sm focus:ring-2 focus:ring-blue-500"
                                      rows="6"
                                      placeholder="Write your code solution here..."
                                      required></textarea>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

                <div class="bottom-4 bg-white p-4 rounded-xl shadow-lg border border-blue-200">
                    <button type="submit" 
                            class="w-full bg-green-500 text-white py-4 px-6 rounded-xl hover:bg-green-600 transition-colors font-semibold text-lg">
                        Submit Assessment
                    </button>
                </div>
            </form>
        </main>

        <script>
            let cameraInterval;
            let formSubmitted = false;

            function initializeTest() {
                // Hide instructions
                document.getElementById('instructionsModal').classList.add('hidden');

                // Configure webcam
                Webcam.set({
                    width: 320,
                    height: 240,
                    image_format: 'jpeg',
                    jpeg_quality: 90,
                    force_flash: false,
                    constraints: {
                        facingMode: "user"
                    }
                });

                Webcam.on('live', () => {
                    document.getElementById('cameraContainer').classList.remove('hidden');
                });

                Webcam.on('error', (err) => {
                    alert('Camera access is required to continue with the assessment! Please enable camera access and refresh the page.');
                    console.error('Camera error:', err);
                });

                Webcam.attach('#my_camera');

                // Start timer
                const startTime = <?= $_SESSION['test_start_time'] ?? 'null' ?>;
                const duration = 30 * 60; // 30 minutes in seconds
                
                function updateTimer() {
                    const now = Math.floor(Date.now() / 1000);
                    const elapsed = now - startTime;
                    const remaining = duration - elapsed;

                    if (remaining <= 0) {
                        document.getElementById('testForm').submit();
                        return;
                    }

                    const minutes = Math.floor(remaining / 60);
                    const seconds = remaining % 60;
                    document.getElementById('timer').textContent = 
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }

                // Update timer every second
                const timerInterval = setInterval(updateTimer, 1000);
                updateTimer();

                // Prevent leaving page
                window.addEventListener('beforeunload', (e) => {
                    if (!formSubmitted) {
                        e.preventDefault();
                        e.returnValue = ''; // For Chrome and other browsers
                    }
                });

                // Cleanup on submit
                document.getElementById('testForm').addEventListener('submit', () => {
                    formSubmitted = true;
                    Webcam.reset();
                    clearInterval(timerInterval);
                    window.removeEventListener('beforeunload', () => {});
                });

                // Force fullscreen mode for desktop
                if (window.innerWidth >= 768) {
                    document.documentElement.requestFullscreen().catch(err => {
                        console.error('Error attempting to enable fullscreen:', err);
                    });
                }
            }

            // Fullscreen warning
            document.addEventListener('fullscreenchange', () => {
                if (!document.fullscreenElement) {
                    alert('Fullscreen mode is required for this assessment!');
                    document.documentElement.requestFullscreen().catch(err => {
                        console.error('Error attempting to enable fullscreen:', err);
                    });
                }
            });
        </script>
    </div>
</body>
</html>
