
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


