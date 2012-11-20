// A container for a tweet object.
var Tweet = Backbone.Model.extend({});

// A basic view rendering a single tweet
var TweetView = Backbone.View.extend({
    tagName: "li",
    className: "tweet",

    render: function() {

        // just render the tweet text as the content of this element.
        $(this.el).html(this.model.id + ": " + this.model.get("text"));
        return this;
    }
});
// Create a StreamCollection
var StreamCollection = Backbone.Collection.extend({
    stream: function(options) {
        
        // Cancel any potential previous stream
        this.unstream();
        
        var _update = _.bind(function() {
            this.fetch(options);
            this._intervalFetch = window.setTimeout(_update, options.interval || 1000);
        }, this);

        _update();
    },

    unstream: function() {
        window.clearTimeout(this._intervalFetch);
        delete this._intervalFetch;
    },
    
    isStreaming : function() {
         return !_.isUndefined(this._intervalFetch);   
    }
});

// A collection holding many tweet objects.
// also responsible for performing the
// search that fetches them.
var Tweets = StreamCollection.extend({
    model: Tweet,
    initialize: function(models, options) {
        this.query = options.query;
    },
    url: function() {
        return "http://search.twitter.com/search.json?q=" + this.query + "&callback=?";
    },
    parse: function(data) {

        // note that the original result contains tweets inside of a results array, not at 
        // the root of the response.
        return data.results;
    },
    add: function(models, options) {
        var newModels = [];
        _.each(models, function(model) {
            if (typeof this.get(model.id) === "undefined") {
                newModels.push(model);
            }
        }, this);
        return Backbone.Collection.prototype.add.call(this, newModels, options);
    }
});

// A rendering of a collection of tweets.
var TweetsView = Backbone.View.extend({
    tagName: "ul",
    className: "tweets",
    initialize: function(options) {
        // Bind on initialization rather than rendering. This might seem
        // counter-intuitive because we are effectively "rendering" this
        // view by creating other views. The reason we are doing this here
        // is because we only want to bind to "add" once, but effectively we should
        // be able to call render multiple times without subscribing to "add" more
        // than once.
        this.collection.bind("add", function(model) {

            var tweetView = new TweetView({
                model: model
            });

            $(this.el).prepend(tweetView.render().el);
        }, this);
    },
    render: function() {
        return this;
    }
});

// Create a new cat tweet collection
var catTweets = new Tweets([], {
    query: "cats"
});

// create a view that will contain our tweets
var catTweetsView = new TweetsView({
    collection: catTweets
});

// We now render this view regardless of the fact it still
// hasn't been fetched. This is because we want to bind to
// the collection and be ready to create the tweet views
// as they come in.
$('#example_content').html(catTweetsView.render().el);

catTweets.stream({interval: 2000, add: true});
