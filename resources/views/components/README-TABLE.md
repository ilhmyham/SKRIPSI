# Cara Menggunakan Komponen Table

## Basic Usage

```blade
<x-table
    :items="$users"
    :columns="[
        ['key' => 'name', 'label' => 'Nama', 'class' => 'font-medium'],
        ['key' => 'email', 'label' => 'Email', 'class' => 'text-gray-600'],
    ]"
/>
```

## With Filter & Search

```blade
<x-table
    :items="$users"
    :columns="[
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'email', 'label' => 'Email'],
        [
            'key' => 'role', 
            'label' => 'Role',
            'badge' => [
                'admin' => 'bg-blue-100 text-blue-700',
                'guru' => 'bg-green-100 text-green-700',
                'siswa' => 'bg-yellow-100 text-yellow-700',
            ]
        ],
    ]"
    filterKey="role"
    :filterOptions="['admin', 'guru', 'siswa']"
    :searchKeys="['name', 'email']"
>
    <x-slot:actions>
        <a :href="`/admin/users/${item.id}/edit`" class="text-blue-600 hover:underline text-sm">
            Edit
        </a>
        <button @click="deleteUser(item.id)" class="text-red-600 hover:underline text-sm">
            Hapus
        </button>
    </x-slot:actions>
</x-table>
```

## With Header Actions

```blade
<x-table :items="$users" :columns="$columns">
    <x-slot:header>
        <x-button href="/admin/users/create" icon="plus">
            Tambah User
        </x-button>
    </x-slot:header>
</x-table>
```

## Props

- `items` - Array/Collection data yang akan ditampilkan
- `columns` - Array konfigurasi kolom:
  - `key` - Key dari data
  - `label` - Label header kolom
  - `class` - CSS class untuk cell (optional)
  - `badge` - Array mapping value => color class untuk badge (optional)
- `filterKey` - Key untuk filtering (optional)
- `filterOptions` - Array opsi filter (optional)
- `searchKeys` - Array keys yang bisa di-search (optional)

## Slots

- `actions` - Slot untuk action buttons (edit, delete, dll)
- `header` - Slot untuk additional header actions
- `footer` - Slot untuk pagination atau footer content
