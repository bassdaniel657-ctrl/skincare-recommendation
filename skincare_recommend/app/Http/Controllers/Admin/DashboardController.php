<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecommendationResult;
use App\Models\User;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use App\Services\UpdateTfidfVectorService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
  /**
   * Show the admin dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    $totalProducts = count(Product::all());
    $totalHistory = count(array_unique(UserQuery::pluck('id')->toArray()));
    $totalUsers = count(User::where('role', 'user')->get());
    $totalFeedback = count(RecommendationResult::where('is_relevant', 1)->orwhere('is_relevant', 0)->get());
    // Add any necessary data or logic here, like fetching statistics or user information

    $endDate = Carbon::now()->endOfDay(); 
    $startDate = Carbon::now()->subDays(6)->startOfDay();
    $dataCounts = DB::table('user_queries')
    ->whereBetween('created_at', [$startDate, $endDate])
    ->select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('COUNT(*) as count')
    )
    ->groupBy('date')
    ->orderBy('date', 'asc')
    ->get();

    $date = array_map(function ($item) {
        return $item['date'];
    }, $dataCounts->select('date')->values()->toArray());
    $count = array_map(function ($item) {
        return $item['count'];
    }, $dataCounts->select('count')->values()->toArray());

    $dataCountFeedback = DB::table('recommendation_results')
    ->whereBetween('created_at', [$startDate, $endDate])
    ->where('is_relevant', !NULL)
    ->select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('COUNT(*) as count'),
    )
    ->groupBy('date')
    ->orderBy('date', 'asc')
    ->get();

    // dump($dataCountFeedback);

    $dateFeedback = array_map(function ($item) {
        return $item['date'];
    }, $dataCountFeedback->select('date')->values()->toArray());
    $countFeedback = array_map(function ($item) {
        return $item['count'];
    }, $dataCountFeedback->select('count')->values()->toArray());

    $startDateMonth = Carbon::now()->subMonths(1)->startOfDay();
    $dataCountMonth = DB::table('user_queries')
    ->whereBetween('created_at', [$startDateMonth, $endDate])
    ->select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('COUNT(*) as count')
    )
    ->groupBy('date')
    ->orderBy('date', 'asc')
    ->get();

    $dateMonth = array_map(function ($item) {
        return $item['date'];
    }, $dataCountMonth->select('date')->values()->toArray());
    $countMonth = array_map(function ($item) {
        return $item['count'];
    }, $dataCountMonth->select('count')->values()->toArray());

    return view('admin.dashboard', [
      'totalProducts' => $totalProducts,
      'totalHistory' => $totalHistory,
      'totalUsers' => $totalUsers,
      'totalFeedback' => $totalFeedback,

      'chartLabels' => json_encode(array_values($date)),
      'chartData' => json_encode(array_values($count)),

      'chartLabelsFeedback' => json_encode(array_values($dateFeedback)),
      'chartDataFeedback' => json_encode(array_values($countFeedback)),

      'chartLabelsMonth' => json_encode(array_values($dateMonth)),
      'chartDataMonth' => json_encode(array_values($countMonth)),
    ]);
  }

  public function updateVector(Request $request)
  {
    // Logika untuk memperbarui sparse vector TF-IDF
    // Misalnya, panggil service yang menangani pembaruan vektor
    UpdateTfidfVectorService::update();

    return response()->json([
      'message' => 'Sparse vector TF-IDF berhasil diperbarui.',
      'status' => 'success'
    ]);
  }

  public function chart(Request $request) {
  {
    $data = [];
    $queryTotal = DB::table('user_queries')
    ->select(
        DB::raw('DATE(created_at) as date'), // Ambil hanya bagian tanggal
        DB::raw('COUNT(*) as count')        // Hitung jumlah user pada tanggal tersebut
    )
    ->groupBy('date') // Kelompokkan berdasarkan tanggal
    ->orderBy('date', 'asc') // Urutkan dari tanggal terlama
    ->get();

    if($request->filter == 1){
      $data = $queryTotal->pluck('count')->toArray();
    }

    return response()->json([
      'data' => $data
    ]);
  }
}
}