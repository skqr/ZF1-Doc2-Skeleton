// Setup a global namespace for our code.
Twitter = Em.Application.create({

  // When everything is loaded.
  ready: function() {

    // Start polling Twitter
    setInterval(function() {
      Twitter.searchResults.refresh();
    }, 2000);

    // The default search is empty, let's find some cats.
    Twitter.searchResults.set("query", "cats");

    // Call the superclass's `ready` method.
    this._super();
  }
});

Twitter.Tweet = Em.Object.extend();

// An instance of ArrayController which handles collections.
Twitter.searchResults = Em.ArrayController.create({

  // Default collection is an empty array.
  content: [],

  // Default query is blank.
  query: null,

  // Simple id-to-model mapping for searches and duplicate checks.
  _idCache: {},

  // Add a Twitter.Tweet instance to this collection.
  // Most of the work is in the built-in `pushObject` method,
  // but this is where we add our simple duplicate checking.
  addTweet: function(tweet) {
    // The `id` from Twitter's JSON
    var id = tweet.get("id");

    // If we don't already have an object with this id, add it.
    if (typeof this._idCache[id] === "undefined") {
      this.pushObject(tweet);
      this._idCache[id] = tweet.id;
    }
  },

  // Public method to fetch more data. Get's called in the loop
  // above as well as whenever the `query` variable changes (via
  // an observer).
  refresh: function() {
    var query = this.get("query");

    // Only fetch if we have a query set.
    if (Em.empty(query)) {
      this.set("content", []);
      return;
    }

    // Poll Twitter
    var self = this;
    var url = "http://search.twitter.com/search.json?q=" + query + "&callback=?";
    $.getJSON(url, function(data) {

      // Make a model for each result and add it to the collection.
      for (var i = 0; i < data.results.length; i++) {
        self.addTweet(Twitter.Tweet.create(data.results[i]));
      }
    });
  }.observes("query")
});

/*Twitter.searchResults = Em.ArrayController.create();
$.getJSON("http://search.twitter.com/search.json?q=cats&callback=?", function(d) {
   Twitter.searchResults.pushObjects(d.results);
});*/