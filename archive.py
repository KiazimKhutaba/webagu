import os
import sys
import tarfile
from gitignore_parser import parse_gitignore
from tqdm import tqdm

def make_tarfile(output_filename, source_dir):
    with tarfile.open(output_filename, "w:gz") as tar:
        for root, dirs, files in os.walk(source_dir):
            gitignore_file = os.path.join(root, '.gitignore')
            gitignore = parse_gitignore(gitignore_file) if os.path.exists(gitignore_file) else lambda x: False

            # Exclude directories and files listed in .gitignore, .git, node_modules, and vendor
            dirs[:] = [d for d in dirs if not gitignore(os.path.join(root, d)) and d not in ['.git', 'node_modules', 'vendor', 'public/upload']]
            files = [f for f in files if not gitignore(os.path.join(root, f))]
            for file in tqdm(files, desc=root):
                tar.add(os.path.join(root, file))

# Get source directory from command-line argument or use current directory
source_dir = sys.argv[1] if len(sys.argv) > 1 else os.getcwd()

# Get output file from command-line argument or use source directory name with .tar.gz extension
output_filename = sys.argv[2] if len(sys.argv) > 2 else os.path.basename(source_dir) + ".tar.gz"

make_tarfile(output_filename, source_dir)
