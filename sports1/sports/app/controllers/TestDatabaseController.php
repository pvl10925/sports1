<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../config/db.php';

class TestDatabaseController extends Controller
{
    public function index()
    {
        $results = [
            'connection' => null,
            'database_info' => null,
            'tables' => null,
            'test_queries' => [],
            'errors' => []
        ];

        try {
            // Test 1: Kết nối database
            $pdo = Database::getConnection();
            $results['connection'] = [
                'status' => 'success',
                'message' => 'Kết nối database thành công!'
            ];

            // Test 2: Thông tin database
            $stmt = $pdo->query("SELECT DATABASE() as db_name, VERSION() as db_version, USER() as db_user");
            $results['database_info'] = $stmt->fetch();

            // Test 3: Liệt kê các bảng
            $stmt = $pdo->query("SHOW TABLES");
            $results['tables'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Test 4: Kiểm tra một số bảng quan trọng
            $importantTables = ['products', 'categories', 'brands', 'accounts', 'orders'];
            foreach ($importantTables as $table) {
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
                    $count = $stmt->fetch()['count'];
                    $results['test_queries'][] = [
                        'table' => $table,
                        'status' => 'success',
                        'row_count' => $count
                    ];
                } catch (PDOException $e) {
                    $results['test_queries'][] = [
                        'table' => $table,
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            }

            // Test 5: Kiểm tra cấu hình PDO
            $results['pdo_attributes'] = [
                'ATTR_ERRMODE' => $pdo->getAttribute(PDO::ATTR_ERRMODE),
                'ATTR_DEFAULT_FETCH_MODE' => $pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE),
                'ATTR_SERVER_VERSION' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
                'ATTR_CLIENT_VERSION' => $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION),
            ];

        } catch (PDOException $e) {
            $results['connection'] = [
                'status' => 'error',
                'message' => 'Lỗi kết nối database: ' . $e->getMessage()
            ];
            $results['errors'][] = $e->getMessage();
        } catch (Exception $e) {
            $results['errors'][] = 'Lỗi: ' . $e->getMessage();
        }

        $this->render(
            'test/database',
            [
                'pageTitle' => 'Test Database Connection',
                'results' => $results
            ],
            'main'
        );
    }
}


