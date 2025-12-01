<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">🔍 Test Database Connection</h1>
            
            <!-- Kết quả kết nối -->
            <?php if ($results['connection']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>1. Kết nối Database</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($results['connection']['status'] === 'success'): ?>
                            <div class="alert alert-success">
                                <strong>✓ Thành công!</strong> <?php echo htmlspecialchars($results['connection']['message']); ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <strong>✗ Lỗi!</strong> <?php echo htmlspecialchars($results['connection']['message']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Thông tin database -->
            <?php if ($results['database_info']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>2. Thông tin Database</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Tên Database:</th>
                                <td><?php echo htmlspecialchars($results['database_info']['db_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Phiên bản MySQL:</th>
                                <td><?php echo htmlspecialchars($results['database_info']['db_version']); ?></td>
                            </tr>
                            <tr>
                                <th>User hiện tại:</th>
                                <td><?php echo htmlspecialchars($results['database_info']['db_user']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Danh sách bảng -->
            <?php if ($results['tables']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>3. Danh sách các bảng (<?php echo count($results['tables']); ?> bảng)</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($results['tables'] as $table): ?>
                                <div class="col-md-3 mb-2">
                                    <span class="badge badge-primary"><?php echo htmlspecialchars($table); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Kiểm tra các bảng quan trọng -->
            <?php if (!empty($results['test_queries'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>4. Kiểm tra các bảng quan trọng</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Bảng</th>
                                    <th>Trạng thái</th>
                                    <th>Số dòng</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results['test_queries'] as $test): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($test['table']); ?></strong></td>
                                        <td>
                                            <?php if ($test['status'] === 'success'): ?>
                                                <span class="badge badge-success">✓ OK</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">✗ Lỗi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (isset($test['row_count'])): ?>
                                                <?php echo number_format($test['row_count']); ?> dòng
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (isset($test['message'])): ?>
                                                <small class="text-danger"><?php echo htmlspecialchars($test['message']); ?></small>
                                            <?php else: ?>
                                                <small class="text-success">Bảng tồn tại và có thể truy vấn</small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Cấu hình PDO -->
            <?php if (isset($results['pdo_attributes'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>5. Cấu hình PDO</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="250">ATTR_ERRMODE:</th>
                                <td><?php echo htmlspecialchars($results['pdo_attributes']['ATTR_ERRMODE']); ?></td>
                            </tr>
                            <tr>
                                <th>ATTR_DEFAULT_FETCH_MODE:</th>
                                <td><?php echo htmlspecialchars($results['pdo_attributes']['ATTR_DEFAULT_FETCH_MODE']); ?></td>
                            </tr>
                            <tr>
                                <th>ATTR_SERVER_VERSION:</th>
                                <td><?php echo htmlspecialchars($results['pdo_attributes']['ATTR_SERVER_VERSION']); ?></td>
                            </tr>
                            <tr>
                                <th>ATTR_CLIENT_VERSION:</th>
                                <td><?php echo htmlspecialchars($results['pdo_attributes']['ATTR_CLIENT_VERSION']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Lỗi (nếu có) -->
            <?php if (!empty($results['errors'])): ?>
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h3>⚠️ Lỗi phát sinh</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <?php foreach ($results['errors'] as $error): ?>
                                <li class="alert alert-danger"><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Nút quay lại -->
            <div class="mb-4">
                <a href="index.php" class="btn btn-secondary">← Quay lại trang chủ</a>
                <a href="index.php?c=test&a=index" class="btn btn-primary">🔄 Test lại</a>
            </div>
        </div>
    </div>
</div>


