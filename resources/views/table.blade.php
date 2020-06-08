@extends('layouts.app')

@section('title', 'Datatable')
@section('content')
  <h1>Datatable</h1>

  <div id="vm">
    <button type="button" @click="getOrders">
      Refresh
    </button>
    <button type="button" @click="ajax('POST', '{{ route('orders.new') }}')">
      Add new order
    </button>
    <button type="button" @click="ajax('DELETE', '{{ route('orders.reset') }}')">
      Reset
    </button>

    <div v-for="order in orders">
      <ul>
        <li v-for="field in order">
          @{{ field[0] }}: @{{ field[1] }}
        </li>
      </ul>
    </div>
  </div>

  <script src="https://vuejs.org/js/vue.js"></script>
  <script>
    new Vue({
      el: '#vm',
      data: {
        orders: [] // id, creator_id, name, fields
      },
      created() {
        this.getOrders()
      },
      methods: {
        getCsrfValue() {
          return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        getOrders() {
          this.ajax('GET', '{{ route("orders") }}')
        },
        async ajax(method, url) {
          try {
            var resp = await fetch(url, {
              method,
              headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': this.getCsrfValue()
              }
            })
            this.orders = await resp.json()
          } catch (err) { console.error(err) }
        }
      }
    })
  </script>
@endsection
