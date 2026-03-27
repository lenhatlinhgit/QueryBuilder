<?php
// tests/QueryBuilderSQLiteTest.php
// File này chứa các bài test (kiểm thử) cho QueryBuilder sử dụng SQLite

use PHPUnit\Framework\TestCase;
// PHPUnit là framework test phổ biến nhất cho PHP
// TestCase là class cha mà mọi class test đều phải kế thừa

// Nạp file kết nối SQLite (nằm cùng thư mục với file test này)
require __DIR__ . '/ConnectionSQLite.php';

// Nạp autoload của Composer để tự động tìm các class trong vendor/
require __DIR__ . '/../vendor/autoload.php';

// Import các class QueryBuilder tương ứng với từng loại câu lệnh SQL
use Tools\Querybuilder\query\QueryBuilderSelect; // Xử lý câu lệnh SELECT
use Tools\Querybuilder\query\QueryBuilderInsert; // Xử lý câu lệnh INSERT
use Tools\Querybuilder\query\QueryBuilderUpdate; // Xử lý câu lệnh UPDATE
use Tools\Querybuilder\query\QueryBuilderDelete; // Xử lý câu lệnh DELETE

// Class test kế thừa TestCase — bắt buộc để PHPUnit nhận diện và chạy các test
class QueryBuilderSQLiteTest extends TestCase
{
    // Biến lưu kết nối PDO dùng chung cho tất cả các test
    private $pdo;

    // setUp() được PHPUnit tự động gọi TRƯỚC MỖI hàm test
    // Mục đích: đảm bảo mỗi test chạy trong môi trường sạch, không bị ảnh hưởng lẫn nhau
    protected function setUp(): void
    {
        // Tạo kết nối tới file SQLite test.db (file DB nhẹ, không cần server)
        $conn = new ConnectionSQLite(__DIR__ . '/test.db');
        $this->pdo = $conn->getPDO(); // Lấy object PDO để thực thi SQL

        // Xóa bảng users nếu đã tồn tại (tránh dữ liệu thừa từ lần test trước)
        $this->pdo->exec("DROP TABLE IF EXISTS users");

        // Tạo lại bảng users với cấu trúc chuẩn trước mỗi test
        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT, -- ID tự tăng, không cần truyền vào
                name TEXT NOT NULL,                   -- Tên người dùng, bắt buộc có
                birth_year INTEGER,                   -- Năm sinh
                age INTEGER,                          -- Tuổi
                address TEXT                          -- Địa chỉ
            )
        ");
    }

    // ========== TEST 1: Kiểm tra chức năng INSERT ==========
    public function testInsertUser()
    {
        // Tạo QueryBuilder cho INSERT, truyền vào kết nối PDO
        $qbInsert = new QueryBuilderInsert($this->pdo);

        $qbInsert->insert('users')          // Chỉ định bảng cần insert
                 ->values(NULL, 'Linh', 2003, 20, 'HN')
                 // NULL → id (tự tăng, SQLite tự xử lý)
                 // 'Linh' → name, 2003 → birth_year, 20 → age, 'HN' → address
                 ->execute(); // Thực thi câu lệnh INSERT vào DB

        // Đếm số dòng trong bảng sau khi insert
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn(); // Lấy giá trị đầu tiên (số lượng)

        // Kiểm tra: phải có đúng 1 dòng sau khi insert
        $this->assertEquals(1, $count);
    }

    // ========== TEST 2: Kiểm tra chức năng SELECT ==========
    public function testSelectUser()
    {
        // Cần insert dữ liệu trước vì setUp() đã xóa sạch bảng
        $qbInsert = new QueryBuilderInsert($this->pdo);
        $qbInsert->insert('users')
                 ->values(NULL, 'Linh', 2003, 20, 'HN')
                 ->execute();

        // Tạo QueryBuilder cho SELECT
        $qbSelect = new QueryBuilderSelect($this->pdo);

        $result = $qbSelect->select('*')        // SELECT * (lấy tất cả cột)
                           ->from('users')       // FROM users
                           ->where('name', '=', 'Linh') // WHERE name = 'Linh'
                           ->get(); // Thực thi và trả về mảng kết quả

        // Kiểm tra: kết quả trả về phải có đúng 1 phần tử
        $this->assertCount(1, $result);

        // Kiểm tra: phần tử đầu tiên phải có name = 'Linh'
        $this->assertEquals('Linh', $result[0]['name']);
    }

    // ========== TEST 3: Kiểm tra chức năng UPDATE ==========
    public function testUpdateUser()
    {
        // Insert dữ liệu gốc trước
        $qbInsert = new QueryBuilderInsert($this->pdo);
        $qbInsert->insert('users')
                 ->values(NULL, 'Linh', 2003, 20, 'HN')
                 ->execute();

        // Tạo QueryBuilder cho UPDATE
        $qbUpdate = new QueryBuilderUpdate($this->pdo);

        $qbUpdate->update('users')                    // UPDATE bảng users
                 ->set('name', '=', 'Linh Updated')   // Đổi name thành 'Linh Updated'
                 ->set('age', '=', 23)                 // Đổi age thành 23
                 ->where('id', '=', 1)                 // Chỉ cập nhật dòng có id = 1
                 ->execute(); // Thực thi câu lệnh UPDATE

        // Dùng PDO thô để kiểm tra kết quả thực sự trong DB
        $stmt = $this->pdo->query("SELECT * FROM users WHERE id=1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Lấy 1 dòng dưới dạng mảng key-value

        // Kiểm tra: name phải đã được cập nhật
        $this->assertEquals('Linh Updated', $row['name']);

        // Kiểm tra: age phải đã được cập nhật
        $this->assertEquals(23, $row['age']);
    }

    // ========== TEST 4: Kiểm tra chức năng DELETE ==========
    public function testDeleteUser()
    {
        // Insert dữ liệu trước để có dữ liệu mà xóa
        $qbInsert = new QueryBuilderInsert($this->pdo);
        $qbInsert->insert('users')
                 ->values(NULL, 'Linh', 2003, 20, 'HN')
                 ->execute();

        // Tạo QueryBuilder cho DELETE
        $qbDelete = new QueryBuilderDelete($this->pdo);

        $qbDelete->delete('users')       // DELETE FROM users
                 ->where('id', '=', 1)   // WHERE id = 1
                 ->execute(); // Thực thi câu lệnh DELETE

        // Đếm lại số dòng sau khi xóa
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();

        // Kiểm tra: bảng phải rỗng (0 dòng) sau khi xóa
        $this->assertEquals(0, $count);
    }
}
// ```

// ---

// **Tóm tắt luồng hoạt động của file này:**
// ```
// setUp() chạy trước mỗi test
//     → Xóa bảng cũ → Tạo bảng mới (môi trường sạch)

// testInsertUser  → Insert 1 dòng → Đếm xem có 1 dòng không
// testSelectUser  → Insert → Select WHERE → Kiểm tra kết quả trả về
// testUpdateUser  → Insert → Update → Đọc lại DB → Kiểm tra đã thay đổi
// testDeleteUser  → Insert → Delete → Đếm xem còn 0 dòng không