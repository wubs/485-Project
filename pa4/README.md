PA 4
====


Building index
--------------


```
class Obj {
   url = url // the id
   caption = caption
}


List<Obj> raw_data_list = "SELECT url, caption from Photo;"

Map<String, List<DocObj>> map = new Map<...>();

for (Obj cur : raw_data_list) {
    for (String word : cur.caption) {
        if ( map.contains(word) ) {
            list_of_docs = map.get(word);
            if cur.url in list_of_docs:
                update (list_of_docs) // calculate TF-IDF
        } else {
            add to list
        }
    }
}
```

Load Caption
------------

Caption laoding is done by a java sub-project called CaptionLoader.
This is a simple Ant project.

`pa4/pa4_files` is the root dir of CaptionLoader.

`pa4/pa4_files/build.xml` shows the options.

To load data,

1. `cd pa4/pa4_files`
2. `ant`

The second step will compile build and run the CaptionLoader.

The source code is at `pa4/pa4_files/src/CaptionLoader.java`.


What this program does is add data into Photo table, Contains table, and Album table.

In Album table, a new album with id=5 will be created, traveler is the owner.

In Photo table, url is `"static/images/" + image_name`, the column code is string `empty` because we will serve image as static file by their url. And, date is created using `NOW()`

In Contains table, captions are loaded from raw_data.
