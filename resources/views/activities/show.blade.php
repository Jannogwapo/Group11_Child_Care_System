                @cannot('isAdmin')
                    <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this activity report?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">Delete Activity</button>
                    </form>
                    <a href="{{ route('activities.edit', $activity) }}" class="edit-btn">Edit Activity</a>
                @endcannot 

<style>
    .edit-btn {
        background: #00b300;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s;
        margin-left: 8px;
    }

    .edit-btn:hover {
        background: #009900;
    }
</style> 