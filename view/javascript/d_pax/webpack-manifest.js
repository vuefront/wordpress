const urljoin = require('url-join');
const path = require('path');
const mkdirp = require('mkdirp');
const fs = require('fs');
const toposort = require('toposort');

class AssetsManifest {
  constructor(options) {
    this.options = {
      path: undefined,
      filename: 'assets-manifest.json',
      extensions: ['js', 'css'],
      prettyPrint: true,
      metadata: undefined,
      ...options,
    };
  }

  attachAfterEmitHook(compiler, callback) {
    // Backwards compatible version of: compiler.plugin.afterEmit.tapAsync()
    if (compiler.hooks) {
      compiler.hooks.afterEmit.tapAsync('webpack-manifest', callback);
    } else {
      compiler.plugin('after-emit', callback);
    }
  }

  apply(compiler) {
    this.attachAfterEmitHook(compiler, (compilation, callback) => {
      const opts = this.options;
      const conf = compilation.options;
      const base = conf.output.publicPath || '';

      const { chunks } = compilation.getStats().toJson();

      let sortedChunks = {};

      if (compilation.chunkGroups) {
        sortedChunks = this.sortChunkGroups(chunks, compilation.chunkGroups);
      } else {
        sortedChunks = this.sortChunks(chunks);
      }

      const manifest = this.mapChunksToManifest(sortedChunks, { publicPath: base });

      const dest = opts.path || conf.output.path;
      const file = path.join(dest, opts.filename);

      const writeFile = (data) => {
        const content = JSON.stringify(data, null, opts.prettyPrint ? 2 : null);
        fs.writeFile(file, content, (err) => {
          if (err) throw err;
          callback();
        });
      };

      mkdirp(dest, () => {
        if (opts.metadata) {
          writeFile({ files: manifest, metadata: opts.metadata });
        } else {
          writeFile({ files: manifest });
        }
      });
    });
  }

  sortChunkGroups(chunks, chunkGroups) {
    const nodeMap = {};
    chunks.forEach((chunk) => {
      nodeMap[chunk.id] = chunk;
    });

    const edges = chunkGroups.reduce((result, chunkGroup) => result.concat(Array.from(chunkGroup.parentsIterable, parentGroup => [parentGroup, chunkGroup])), []);
    const sortedGroups = toposort.array(chunkGroups, edges);
    // flatten chunkGroup into chunks
    const sortedChunks = sortedGroups
      .reduce((result, chunkGroup) => result.concat(chunkGroup.chunks), [])
      .map(chunk => nodeMap[chunk.id])
      .filter((chunk, index, self) => {
        // make sure exists (ie excluded chunks not in nodeMap)
        const exists = !!chunk;
        // make sure we have a unique list
        const unique = self.indexOf(chunk) === index;
        return exists && unique;
      });
    return sortedChunks;
  }

  sortChunks(chunks) {
    const nodes = {};

    chunks.forEach((chunk) => {
      nodes[chunk.id] = chunk;
    });

    // Next, we add an edge for each parent relationship into the graph
    const edges = [];

    chunks.forEach((chunk) => {
      if (chunk.parents) {
        // Add an edge for each parent (parent -> child)
        chunk.parents.forEach((parentId) => {
          // webpack2 chunk.parents are chunks instead of string id(s)
          const parentChunk = nodes[parentId];
          // If the parent chunk does not exist (e.g. because of an excluded chunk)
          // we ignore that parent
          if (parentChunk) {
            edges.push([parentChunk, chunk]);
          }
        });
      }
    });

    // We now perform a topological sorting on the input chunks and built edges
    return toposort.array(chunks, edges);
  }

  mapChunksToManifest(chunks, manifestOptions = {}) {
    const options = {
      publicPath: '',
      ...manifestOptions,
    };


    const nextElement = ([head, ...tail]) => {
      if (!head) {
        return null;
      }

      return {
        id: head.id,
        next: nextElement(tail),
        ...this.options.extensions.reduce((acc, extension) => ({
          ...acc,
          ...this.filterChunkFilesByExtension(head.files, extension, options.publicPath),
        }), {}),
      };
    };

    return nextElement(chunks);
  }

  filterChunkFilesByExtension(files, extension, publicPath) {
    return {
      [extension]: files
        .filter(file => file.endsWith(extension))
        .map(file => (publicPath.length === 0 ? file : urljoin(publicPath, file))),
    };
  }
}

module.exports = AssetsManifest;
