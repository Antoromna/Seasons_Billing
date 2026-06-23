@extends('layouts.auth')

@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
              <div>
                {{-- <p class="eyebrow mb-1">Overview</p> --}}
                <h1 class="h3 mb-1">Dashboard</h1>
                {{-- <p class="text-muted mb-0">Monitor performance, sales, users, and support from one clean workspace.</p> --}}
              </div>
            </div>
            {{-- <div class="heading-actions"><button class="btn btn-outline-secondary btn-sm" type="button"><i class="bi bi-download" aria-hidden="true"></i> Export</button><button class="btn btn-primary btn-sm" type="button"><i class="bi bi-file-earmark-plus" aria-hidden="true"></i> Create Report</button></div> --}}
          </div>
          <section class="row g-3 mt-1">

              <!-- Customers -->
              <div class="col-12 col-sm-6 col-xl-3">
                  <article class="metric-card metric-primary">
                      <div class="metric-top">
                          <span class="metric-label">Customers</span>
                          <span class="metric-icon">
                              <i class="bi bi-people"></i>
                          </span>
                      </div>
                      <div class="metric-value">{{ $customerCount }}</div>
                      <div class="metric-meta">
                          <span>Total Customers</span>
                      </div>
                  </article>
              </div>

              <!-- Products -->
              <div class="col-12 col-sm-6 col-xl-3">
                  <article class="metric-card metric-success">
                      <div class="metric-top">
                          <span class="metric-label">Products</span>
                          <span class="metric-icon">
                              <i class="bi bi-box-seam"></i>
                          </span>
                      </div>
                      <div class="metric-value">{{ $productCount }}</div>
                      <div class="metric-meta">
                          <span>Total Products</span>
                      </div>
                  </article>
              </div>

          </section>


          <section class="panel mt-3">
            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Recent Users</span></h2>
                {{-- <p class="text-muted mb-0">Latest account activity across the workspace.</p> --}}
              </div>
              <a class="btn btn-outline-secondary btn-sm" href="{{route('customers.index')}}">Manage Users</a>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead><tr><th scope="col">User</th><th scope="col">Status</th><th scope="col">Joined</th></tr></thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <p class="fw-semibold mb-0">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>

                        

                        <td>
                            <span class="badge text-bg-success">
                                Active
                            </span>
                        </td>

                        <td>{{ $user->created_at->format('d M, Y') }}</td>

                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No users found</td>
                    </tr>
                    @endforelse
                    </tbody>
              </table>
            </div>
          </section>
        </div>
    </main>
@endsection