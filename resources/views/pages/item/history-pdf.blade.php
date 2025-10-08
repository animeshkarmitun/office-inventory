<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Item History - {{ $item->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #f8f9fa;
            padding: 10px;
            margin: 0 0 15px 0;
            font-weight: bold;
            font-size: 14px;
            border-left: 4px solid #007bff;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 8px;
            font-weight: bold;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .info-value {
            display: table-cell;
            width: 70%;
            padding: 8px;
            border: 1px solid #dee2e6;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-primary { background-color: #cce7ff; color: #004085; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-secondary { background-color: #e2e3e5; color: #383d41; }
        
        .movement-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .movement-table th,
        .movement-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        
        .movement-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Item History Report</h1>
        <p>{{ $item->name }} - Generated on {{ date('M d, Y H:i') }}</p>
    </div>

    <!-- Item Information Section -->
    <div class="section">
        <div class="section-title">Item Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Item Name:</div>
                <div class="info-value">{{ $item->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Serial Number:</div>
                <div class="info-value">{{ $item->serial_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Asset Tag:</div>
                <div class="info-value">{{ $item->asset_tag ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Barcode:</div>
                <div class="info-value">{{ $item->barcode ?: $item->asset_tag ?: 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">RFID Tag:</div>
                <div class="info-value">{{ $item->rfid_tag ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Category:</div>
                <div class="info-value">{{ $item->category->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Asset Type:</div>
                <div class="info-value">{{ $item->asset_type ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Description:</div>
                <div class="info-value">{{ $item->description ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Specifications:</div>
                <div class="info-value">{{ $item->specifications ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Condition:</div>
                <div class="info-value">{{ $item->condition ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="badge badge-{{ $item->status == 'available' ? 'success' : ($item->status == 'in_use' ? 'primary' : ($item->status == 'maintenance' ? 'warning' : ($item->status == 'disposed' ? 'danger' : 'secondary'))) }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Value:</div>
                <div class="info-value">{{ $item->value ? '$' . number_format($item->value, 2) : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Quantity:</div>
                <div class="info-value">{{ $item->quantity ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Assigned User:</div>
                <div class="info-value">{{ $item->assignedUser->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Current Floor:</div>
                <div class="info-value">{{ $item->floor->name ?? $item->floor_level ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Current Room:</div>
                <div class="info-value">{{ $item->room->name ?? $item->room_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Location:</div>
                <div class="info-value">{{ $item->location ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Remarks:</div>
                <div class="info-value">{{ $item->remarks ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Purchase Information Section -->
    <div class="section">
        <div class="section-title">Purchase Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Supplier:</div>
                <div class="info-value">{{ $item->supplier->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Purchase Date:</div>
                <div class="info-value">{{ $item->purchase_date ? $item->purchase_date->format('M d, Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Purchased By:</div>
                <div class="info-value">{{ $item->purchasedBy->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Received By:</div>
                <div class="info-value">{{ $item->receivedBy->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Depreciation Method:</div>
                <div class="info-value">{{ $item->depreciation_method ? ucfirst(str_replace('_', ' ', $item->depreciation_method)) : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Depreciation Rate:</div>
                <div class="info-value">{{ $item->depreciation_rate ? $item->depreciation_rate . '%' : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Depreciation Cost:</div>
                <div class="info-value">{{ $item->depreciation_cost ? '$' . number_format($item->depreciation_cost, 2) : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Current Book Value:</div>
                <div class="info-value">{{ $item->currentBookValue() ? '$' . number_format($item->currentBookValue(), 2) : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Approval Information Section -->
    @if($item->is_approved || $item->approved_by)
    <div class="section">
        <div class="section-title">Approval Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Approval Status:</div>
                <div class="info-value">
                    <span class="badge badge-{{ $item->is_approved ? 'success' : 'warning' }}">
                        {{ $item->is_approved ? 'Approved' : 'Pending' }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Approved By:</div>
                <div class="info-value">{{ $item->approvedBy->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Approved At:</div>
                <div class="info-value">{{ $item->approved_at ? $item->approved_at->format('M d, Y H:i') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tracking Mode:</div>
                <div class="info-value">{{ $item->tracking_mode ? ucfirst(str_replace('_', ' ', $item->tracking_mode)) : 'N/A' }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Movement History Section -->
    <div class="section">
        <div class="section-title">Movement History</div>
        @if($movements->isEmpty())
            <div class="no-data">No movement history found for this item.</div>
        @else
            <table class="movement-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>From User</th>
                        <th>To User</th>
                        <th>From Location</th>
                        <th>To Location</th>
                        <th>Moved By</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $move)
                        <tr>
                            <td>{{ $move->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $move->fromUser->name ?? 'N/A' }}</td>
                            <td>{{ $move->toUser->name ?? 'N/A' }}</td>
                            <td>{{ $move->from_location ?? 'N/A' }}</td>
                            <td>{{ $move->to_location ?? 'N/A' }}</td>
                            <td>{{ $move->movedBy->name ?? 'N/A' }}</td>
                            <td>{{ $move->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="footer">
        <p>This report was generated on {{ date('M d, Y H:i') }} by the Office Inventory Management System</p>
    </div>
</body>
</html>
