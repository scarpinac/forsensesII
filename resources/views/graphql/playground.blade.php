@extends('layouts.adminlte-with-language')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3>GraphQL Playground</h3>
            <p>Teste as queries GraphQL para obter dados do Grafana</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Query</h5>
                </div>
                <div class="card-body">
                    <textarea id="queryInput" class="form-control" rows="20">
{
  dashboard(id: "UID_DO_DASHBOARD") {
    id
    title
    panels {
      id
      title
      type
      iframeUrl(from: "now-7d", to: "now")
    }
  }
}</textarea>
                </div>
                <div class="card-footer">
                    <button onclick="executeQuery()" class="btn btn-primary">Execute Query</button>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Result</h5>
                </div>
                <div class="card-body">
                    <pre id="resultOutput" class="bg-light p-3" style="min-height: 400px; max-height: 600px; overflow-y: auto;">{
  "data": {
    "dashboard": {
      "id": "abc123",
      "title": "Dashboard Example",
      "panels": [...]
    }
  }
}</pre>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Exemplos de Queries</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Obter todos os dashboards:</h6>
                            <pre class="bg-light p-2">{
  dashboards {
    id
    title
  }
}</pre>
                        </div>
                        <div class="col-md-6">
                            <h6>Obter dados específicos do painel:</h6>
                            <pre class="bg-light p-2">{
  panelData(
    dashboardId: "UID_DO_DASHBOARD",
    panelId: 1,
    from: "now-7d",
    to: "now"
  ) {
    timestamp
    value
    metric
  }
}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function executeQuery() {
    const query = document.getElementById('queryInput').value;
    const resultOutput = document.getElementById('resultOutput');
    
    fetch('/graphql', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            query: query
        })
    })
    .then(response => response.json())
    .then(data => {
        resultOutput.textContent = JSON.stringify(data, null, 2);
    })
    .catch(error => {
        resultOutput.textContent = 'Error: ' + error.message;
    });
}
</script>
@endsection
