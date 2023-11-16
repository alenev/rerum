<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocumentsController extends Controller
{
    public function index()
    {
        $indexBuilt = session('indexBuilt', false);
        $filename = session('filename', null);

        return view('documents.index', compact('indexBuilt', 'filename'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:json|max:10240',
        ]);

        $filename = $this->storeUploadedFile($request->file('file'));

        return view('documents.index', ['filename' => $filename]);
    }

    public function buildIndex($filename)
    {
        $contents = Storage::get("uploads/documents/{$filename}");
        $documents = json_decode($contents, true);

        $overallIndex = $this->getOverallIndex();
        $this->buildSearchIndex($documents, 'name', $filename, $overallIndex);
        $this->storeOverallIndexFile($overallIndex);

        return view('documents.index', ['indexBuilt' => true, 'filename' => $filename]);
    }

    private function buildSearchIndex($documents, $key, $filename, &$overallIndex)
    {
        foreach ($documents as $document) {
            if (array_key_exists($key, $document)) {
                $value = $document[$key];
                if (!isset($overallIndex[$value])) {
                    $overallIndex[$value] = [];
                }
                $this->updateIndex($overallIndex[$value], $filename); 
            }
        }
    }

    private function updateIndex(&$indexArray, $newFilename)
    {
        foreach ($indexArray as &$existingFilename) {
            if (pathinfo($existingFilename, PATHINFO_FILENAME) === pathinfo($newFilename, PATHINFO_FILENAME)) {
                $existingFilename = $newFilename;
                return;
            }
        }
        $indexArray[] = $newFilename;
    }


    private function getOverallIndex()
    {
        $overallIndexFilename = "indexes/overallIndex.json";
        $overallIndexContents = Storage::get($overallIndexFilename);
        return json_decode($overallIndexContents, true) ?? [];
    }
    private function storeOverallIndexFile($overallIndex)
    {
        $overallIndexFilename = "indexes/overallIndex.json";
        Storage::put($overallIndexFilename, json_encode($overallIndex));
    }

    public function search(Request $request)
    {
        $filename = $request->input('filename');
        $searchValue = $request->input('searchValue');
        $useIndex = $request->has('useIndex');

        if ($useIndex) {
            $indexFilename = $this->getIndexFilename($filename);
            $result = $this->searchWithIndex($searchValue);
        } else {
            $documents = $this->getDocumentsFromFilename($filename);
            $result = $this->searchWithoutIndex($searchValue);
        }

        return view('documents.results', compact('result', 'useIndex'));
    }

    private function storeUploadedFile($file)
    {
        $time = Carbon::now()->format('YmdHs');
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $originalFilename . '_' . $time . '.json';

        $file->storeAs('uploads/documents', $filename);

        return $filename;
    }

    private function storeIndexFile($filename, $index)
    {
        $indexFilename = "indexes/overallIndex.json";

        $existingIndex = Storage::exists($indexFilename)
            ? json_decode(Storage::get($indexFilename), true)
            : [];

        $existingIndex = array_merge($existingIndex, $index);

        Storage::put($indexFilename, json_encode($existingIndex));

        return $indexFilename;
    }


    private function searchWithoutIndex($value)
    {
        $result = [];
        $comparisonCount = 0;
        $files = Storage::files('uploads/documents');

        foreach ($files as $file) {

            $contents = Storage::get($file);
            $document = json_decode($contents, true);


            if (is_array($document)) {
                foreach ($document as $key => $doc) {
                    $comparisonCount++;
                    if ($doc["name"] === $value) {
                        $result[] = $doc;
                    }
                }
            }
        }


        return compact('result', 'comparisonCount');
    }


    private function getDocumentSearchData($file, $value)
    {
        if(strpos($file,'json') === false) {
            $file = $file.'.json';
        }
        $contents = json_decode(Storage::get('uploads/documents/' . $file), true);
        $data = [];
        if (!empty($contents)) {
            foreach ($contents as $key => $content) {
                if ($content["name"] == $value) {
                    $data[] = $content;
                }
            }
        }
        return $data;
    }

    private function searchWithIndex($value)
    {
        $result = [];
        $comparisonCount = 0;
        $indexFilename = "indexes/overallIndex.json";

        if (Storage::exists($indexFilename)) {
            $overallIndex = json_decode(Storage::get($indexFilename), true);

            $matchingFiles = [];
            foreach ($overallIndex as $key => $files) {
                $comparisonCount++;
                if ($key == $value) {

                    if (count($files) > 1) {
                        foreach ($files as $key => $file) {
                            $matchingFiles[] = $file;
                        }
                    } else {
                        $matchingFiles[] = $files[0];
                    }
                }
            }

            if (count($matchingFiles) > 0) {

                foreach ($matchingFiles as $file) {
                    if (is_array($file)) {
                        foreach ($file as $key => $filename) {
                            $result[] = $this->getDocumentSearchData($filename, $value);                 
                        }
                    } else {
                        $filename = $file;
                        $result[] = $this->getDocumentSearchData($filename, $value);
                    }

                }
            }
        }

        return compact('result', 'comparisonCount');
    }


    private function getIndexFromFile($indexFilename)
    {
        $indexContents = Storage::get($indexFilename);

        return json_decode($indexContents, true);
    }



    private function getDocumentsFromFilename($filename)
    {
        $contents = Storage::get("uploads/documents/{$filename}");

        return json_decode($contents, true);
    }

    private function getIndexFilename($filename)
    {
        return "indexes/index_{$filename}.json";
    }

    public function searchForm()
    {
        $indexBuilt = session('indexBuilt', false);
        $filename = session('filename', null);

        return view('documents.search-form', compact('indexBuilt', 'filename'));
    }

    private function getAllDocuments()
    {
        $documents = [];
        $files = Storage::files('uploads/documents');

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $contents = json_decode(Storage::get($file), true);
            $documents[$filename] = $contents;
        }

        return $documents;
    }

    public function rebuildIndex()
    {
        $documents = $this->getAllDocuments();
        $overallIndex = [];

        foreach ($documents as $filename => $document) {
            $this->buildSearchIndex($document, 'name', $filename, $overallIndex);
        }

        $this->storeOverallIndexFile($overallIndex);

        return redirect()->route('documents.searchForm')->with('indexBuilt', true);
    }


}
