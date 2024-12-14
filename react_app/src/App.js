import React, { useState, useEffect } from "react";

function App() {
  const [bookmarks, setBookmarks] = useState([]);
  const [newBookmark, setNewBookmark] = useState({ title: "", link: "" });
  const [editingBookmark, setEditingBookmark] = useState(null);

  // Fetch all bookmarks
  useEffect(() => {
    fetch("http://localhost:9000/api/readall.php")
      .then((response) => response.json())
      .then((data) => setBookmarks(data))
      .catch((error) => console.error("Error fetching bookmarks:", error));
  }, []);

  // Handle create
  const handleCreate = () => {
    fetch("http://localhost:9000/api/create.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(newBookmark),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        setBookmarks([...bookmarks, newBookmark]);
        setNewBookmark({ title: "", link: "" });
      })
      .catch((error) => console.error("Error creating bookmark:", error));
  };

  // Handle delete
  const handleDelete = (id) => {
    fetch(`http://localhost:9000/api/delete.php?id=${id}`, {
      method: "DELETE",
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        setBookmarks(bookmarks.filter((bookmark) => bookmark.id !== id));
      })
      .catch((error) => console.error("Error deleting bookmark:", error));
  };

  // Handle edit
  const handleStartEdit = (bookmark) => {
    setEditingBookmark(bookmark);
    setNewBookmark({ title: bookmark.title, link: bookmark.link });
  };

  // Handle update
  const handleUpdate = (id) => {
    const updatedBookmark = { ...newBookmark, id };

    fetch("http://localhost:9000/api/update.php", {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(updatedBookmark),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        setBookmarks(
          bookmarks.map((bookmark) =>
            bookmark.id === id ? updatedBookmark : bookmark
          )
        );
        setNewBookmark({ title: "", link: "" });
        setEditingBookmark(null); 
      })
      .catch((error) => console.error("Error updating bookmark:", error));
  };

  return (
    <div>
      <h1>Bookmarks App</h1>
      <div>
        <input
          type="text"
          placeholder="Title"
          value={newBookmark.title}
          onChange={(e) => setNewBookmark({ ...newBookmark, title: e.target.value })}
        />
        <input
          type="text"
          placeholder="Link"
          value={newBookmark.link}
          onChange={(e) => setNewBookmark({ ...newBookmark, link: e.target.value })}
        />
        <button onClick={handleCreate}>Create</button>
      </div>

      <h2>{editingBookmark ? "Update Bookmark" : "Bookmarks List"}</h2>
      {editingBookmark && (
        <div>
          <input
            type="text"
            value={newBookmark.title}
            onChange={(e) => setNewBookmark({ ...newBookmark, title: e.target.value })}
            placeholder="New title"
          />
          <input
            type="text"
            value={newBookmark.link}
            onChange={(e) => setNewBookmark({ ...newBookmark, link: e.target.value })}
            placeholder="New link"
          />
          <button onClick={() => handleUpdate(editingBookmark.id)}>Update</button>
          <button onClick={() => setEditingBookmark(null)}>Cancel</button>
        </div>
      )}

      <ul>
        {bookmarks.map((bookmark) => (
          <li key={bookmark.id}>
            <span>{bookmark.title}</span> - <a href={bookmark.link}>{bookmark.link}</a>
            <button onClick={() => handleDelete(bookmark.id)}>Delete</button>
            <button onClick={() => handleStartEdit(bookmark)}>Edit</button>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;
