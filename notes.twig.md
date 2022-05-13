# LOOP 

loop.index	    The current iteration of the loop. (1 indexed)
loop.index0	    The current iteration of the loop. (0 indexed)
loop.revindex	The number of iterations from the end of the loop (1 indexed)
loop.revindex0	The number of iterations from the end of the loop (0 indexed)
loop.first	    True if first iteration
loop.last	    True if last iteration
loop.length	    The number of items in the sequence
loop.parent	    The parent context

{% for user in users %}
    {{ loop.index }} - {{ user.username }}
{% endfor %}