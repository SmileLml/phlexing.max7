#!/bin/bash
# set env
export PATH="/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin:$PATH"
echo pwd
TARGET_DIR="/opt/zbox/app/zentaomax"
LINK_DIR="/opt/link/zentao"
#file list
FILES=(
    "extension/max/feedback/ui/create.html.php"
    "extension/max/feedback/ui/edit.html.php"
    "extension/max/approval/lang/zh-cn.php"
    "module/action/config.php"
    "module/action/model.php"
    "module/block/lang/zh-cn.php"
    "module/bug/config/form.php"
    "module/bug/ui/create.field.php"
    "module/my/model.php"
    "module/my/tao.php"
    "module/my/ui/audit.html.php"
    "module/productplan/lang/zh-cn.php"
    "module/story/config/form.php"
    "module/story/ui/create.field.php"
    "module/story/ui/edit.html.php"
    "module/task/config/form.php"
    "module/task/lang/zh-cn.php"
    "module/task/ui/batchcreate.html.php"
    "module/task/ui/create.field.php"
    "module/task/zen.php"
    "module/transfer/model.php"
)
# file
for file in "${FILES[@]}";do
    echo "relative path :$file"
    file_path="$TARGET_DIR/$file"
    link_file_path="$LINK_DIR/$file"
    echo "link path: $link_file_path"
    # get filename
    file_name=$(basename "$file")
    echo "get filename: $file_name"
    # check file exit
    if [[ -e "$file_path" && -L "$file_path" ]]; then
        echo " existing_links: $file_path"
        unlink $file_path
    else
        echo "not link"
        exit 1
    fi
    if [[ -e "$file_path.bak" ]]; then
        echo "found: $file.bak"
        #recover name
        recover_name="$TARGET_DIR/${file}"

        # rename
        mv "$file_path.bak" "$recover_name"
        echo "  recover name: $recover_name"
        echo "--end--"
    else
        echo "Not Found File: $file.bak"
    fi
done

