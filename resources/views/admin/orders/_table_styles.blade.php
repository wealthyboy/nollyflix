<style>
   .orders-card {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 5px 22px rgba(29, 36, 50, .08);
   }
   .orders-card .card-content { padding: 26px; }
   .orders-title { margin: 0 0 22px; font-weight: 700; color: #2f3441; }
   .orders-table-wrap { width: 100%; overflow-x: auto; border: 1px solid #e7e9ee; border-radius: 10px; }
   .orders-table { margin: 0 !important; background: #fff; }
   .orders-table > thead > tr > th,
   .orders-table > thead > tr > td {
      padding: 15px 14px !important;
      border-bottom: 2px solid #dde1e8 !important;
      background: #f4f6f9;
      color: #282d37 !important;
      font-size: 12px !important;
      font-weight: 800 !important;
      letter-spacing: .45px;
      line-height: 1.35;
      text-transform: uppercase;
      white-space: nowrap;
   }
   .orders-table > tbody > tr > td {
      padding: 15px 14px !important;
      border-bottom: 1px solid #eceef2 !important;
      color: #4c5260;
      font-size: 14px;
      vertical-align: middle !important;
   }
   .orders-table > tbody > tr:last-child > td { border-bottom: 0 !important; }
   .orders-table > tbody > tr:hover > td { background: #fafbfc !important; }
   .orders-table .order-invoice { color: #222733; font-weight: 700; }
   .orders-table .order-total { color: #161a22; font-weight: 800; white-space: nowrap; }
   .orders-table .btn { margin: 0; border-radius: 7px; }
   .orders-empty { padding: 38px 20px !important; color: #8b909b !important; text-align: center; }
   .orders-card .dataTables_filter input,
   .orders-card .dataTables_length select { border-radius: 7px; }
   @media (max-width: 767px) {
      .orders-card .card-content { padding: 18px 14px; }
      .orders-table > thead > tr > th,
      .orders-table > tbody > tr > td { padding: 12px 10px !important; }
   }
</style>
