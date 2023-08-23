<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Report</title>

</head>

<body>
    <table class="w-full whitespace-nowrap">
        <thead>
            <tr class="font-bold text-left">
                <th class="px-6 pt-5 pb-4">Full Name</th>
                <th class="px-6 pt-5 pb-4">Username</th>
                <th class="px-6 pt-5 pb-4">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $user)
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <a href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">
                            {{ $user->full_name }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">
                            {{ $user->username }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">
                            {{ $user->email }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
